@extends('layouts.layout')
@section('email')
    <tr>
        <td style="padding:0 0 36px 0;color:#153643;">
            <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">{{ trans('mail.mail.mail_complete') }}
            </h1>
            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                {{ trans('mail.mail.mail_thanks') }}</p>
            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a
                    href="{{ $verificationUrl }}">{{ trans('mail.mail.mail_alert_click') }}</a></p>
            <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;color:#ee4c50;">
                {{ trans('mail.mail.attention') }}</p>
            <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;"><a href="https://nal.vn/vi/"
                    style="color:#ee4c50;text-decoration:underline;">{{ trans('auth.nal') }}</a></p>
        </td>
    </tr>
@endsection
