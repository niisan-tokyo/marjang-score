<html>
    <head>
        <title>ログイン</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="container">
            <form action="{{ route('login-password-post') }}" method="post">
                @csrf
                <label>メールアドレス<input type="text" name="email" /></label><br>
                <label>パスワード<input type="password" name="password" /></label><br>
                <button type="submit">ログイン</button>
            </form>
        </div>
    </body>
</html>