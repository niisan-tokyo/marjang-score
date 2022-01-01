# install
マニュアルサイトにある

```
curl -s https://laravel.build/example-app | bash
```
は、windowsでそのまま使えんかったので、以下の方法でインストール

```
docker run --rm -v C:/my/projects:/opt -w /opt laravelsail/php81-composer:latest  bash -c "laravel new roxxmarjang3 && cd roxxmarjang3 && php ./artisan sail:install --with=mysql,redis,meilisearch,mailhog,selenium --devcontainer"
```
laravel入りのコンテナの名前が変わる可能性あるので、あまりいいものではないけど。

終わったら.devcontainer作られているので、VSCでコンテナに入る。
