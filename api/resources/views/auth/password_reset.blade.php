<x-mail::message>
# パスワード再設定のお知らせ

パスワード再設定のリクエストがありました。

以下のリンクをクリックしてパスワードを再設定してください。
<x-mail::button :url="env('NUXT_URL', 'http://localhost').'/reset-password/'.$token">
パスワード再設定
</x-mail::button>
有効期限は{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }}分間です。

もしこのメールにお心当たりがない場合は、無視してください。
</x-mail::message>
