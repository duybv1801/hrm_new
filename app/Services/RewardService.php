<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Reward;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\UserRepository;

class RewardService
{
    public function __construct(
        UserRepository $userRepo
    ) {
        $this->userRepository = $userRepo;
    }

    public function import(UploadedFile $file, $time): bool|Reward
    {
        $contents = file_get_contents($file->path());
        $lines = explode("\n", $contents);

        $users = $this->userRepository->all([], null, null, ['id', 'name', 'code']);

        array_shift($lines);
        DB::beginTransaction();
        try {
            $dataToInsert = [];
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line)) {
                    $data = str_getcsv($line);
                    $code = trim($data[1]);
                    $reason = trim($data[2]);
                    $money = trim($data[3]);
                    $user = $users->firstWhere('code', $code);
                    if ($user) {
                        $user_id = $user->id;

                        $dataToInsert[] = [
                            'time' => $time,
                            'reason' => $reason,
                            'user_id' => $user_id,
                            'money' => $money,
                        ];
                    }
                    Log::alert($code);
                    Log::alert($reason);
                    Log::alert($money);

                }
            }
            if ($result = Reward::insert($dataToInsert)) {
                DB::commit();
                return $result;
            }
            return false;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Error "importReward" : ' . $ex->getMessage() . ' - ' . $ex->getLine() . ' - ' . $ex->getFile());
            return false;
        }
    }
}
