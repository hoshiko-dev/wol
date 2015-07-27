<?php
require "vendor/autoload.php";

use Slim\Slim;
use Slim\Extras\Views\Twig as Twig;

$log = new Monolog\Logger('log');
$log->pushHandler(new Monolog\Handler\StreamHandler('log/wol.log', Monolog\Logger::INFO));

$app = new \Slim\Slim([
              'view' => new Twig,
              'templates.path' => 'template'
            ]);
//
// request
//
$app->get("/", function() use($app,$log) {
  require_once 'app/models/WakeUpLanManager.php';
  $wol = new WakeUpLanManager($log);
  echo $app->render('index.html',['wol_list'=>$wol->getWolJson()]);
});

$app->get("/wakeup/:id", function($id) use($app,$log) {
  $log->info('START');
  require_once 'app/models/WakeUpLanManager.php';
  $wol = new WakeUpLanManager($log);
  // WOL実行
  $result = $wol->postWol($id,$wol->getWolJson());
  $app->response->headers->set('Content-Type', 'application/json');
  $app->response->setBody(json_encode(['result' => 'ok']));
});

$app->get("/ping/:id", function($id = null) use($app,$log) {
  $log->info('START');
  require_once 'app/models/WakeUpLanManager.php';
  $wol = new WakeUpLanManager($log);
  /// ping チェック
  if (empty($id)) {
    $result = $wol->pingCmds($wol->getWolJson());
  } else {
    $info = $wol->getWolJsonInfoById($id);
    $result = ['id' => $id,'result' => false];
    if (!empty($info)) {
      $result = $wol->pingCmd($id,$info['ipAddr']);
    }
  }
  $app->response->headers->set('Content-Type', 'application/json');
  $app->response->setBody(json_encode($result));
  // パラメータ無し=All
})->name('ping')->conditions(array('id' => '\w*'));

$app->run();
