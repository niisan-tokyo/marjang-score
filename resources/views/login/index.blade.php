<html>
    <head>
        <title>ログイン</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container">
            <a href="{{ route('login-password') }}">普通のログインはこちら</a><br>
            <form action="{{ route('login-publish') }}" method="post">
                @csrf
                <label>メールアドレス<input type="text" name="email" /></label><br>
                <button type="submit">ログインURLを発行する</button>
            </form>
        </div>
        * ログインURLの有効期限は10分ね
    </body>
</html>