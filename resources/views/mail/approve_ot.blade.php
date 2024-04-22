@extends('layouts.layout')
@section('email')
    <tr>
        <td style="padding: 0 0 36px 0; color: #153643;">
            <div style="background-color: #f2f2f2; padding: 30px; border-radius: 4px;">
                <table style="width: 100%; font-family: Arial, sans-serif;">
                    <tr>
                        <td style="font-size: 18px; font-weight: bold; padding-bottom: 10px;">
                            {{ trans('overtime.user') }}: {{ $overtime->user->name }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; font-weight: bold; padding-bottom: 10px;">
                            {{ trans('overtime.status') }}: {{ trans('overtime.label ' . $overtime->status) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; padding-bottom: 10px;">{{ trans('overtime.from') }}:
                            {{ $overtime->from_datetime }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; padding-bottom: 10px;">{{ trans('overtime.to') }}:
                            {{ $overtime->to_datetime }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; padding-bottom: 10px;">{{ trans('overtime.approver') }}:
                            {{ $overtime->approver->name }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 18px; padding-bottom: 10px;">
                            @if (
                                $overtime->status == config('define.overtime.registered') ||
                                    $overtime->status == config('define.overtime.cancel') ||
                                    $overtime->status == config('define.overtime.confirm'))
                                {{ trans('overtime.reason') }}: {{ $overtime->reason }}
                            @else
                                {{ trans('overtime.comment') }}: {{ $overtime->comment }}
                            @endif
                        </td>
                    </tr>
                </table>
                <p style="margin: 20px 0 12px; font-size: 14px; line-height: 20px; font-family: Arial, sans-serif;">
                    {{ trans('mail.mail.mail_thanks') }}</p>
                <p style="margin: 0; font-size: 14px; line-height: 20px; font-family: Arial, sans-serif;">
                    <a href="https://nal.vn/vi/"
                        style="color: #ee4c50; text-decoration: underline;">{{ trans('auth.nal') }}</a>
                </p>
            </div>
        </td>
    </tr>
@endsection
