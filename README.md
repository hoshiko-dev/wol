# wol
自宅内でWakeUpLanでSleepさせたPCを起動させるためのWebアプリ
PCの起動・停止チェックはping

## 環境

* PHP5.6で確認
* DB不要
* wakeonlanコマンド等wolコマンドをサーバにインストールする
* composerで初回インストールすること
> composer install

## 設定
* 環境はjsonファイルで設定する
* IPアドレスとMacアドレス、wol用のコマンドをJSONファイルに指定

## その他
* フレームワークにはSlimを使用
* TemplateEngineはTwig
* クライアント側はjQueryのみ
