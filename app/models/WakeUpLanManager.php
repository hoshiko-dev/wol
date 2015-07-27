<?php

Class WakeUpLanManager {

  private $json;
  private $logger;

  public function __construct($logger = null) {
    $this->logger = $logger;
  }
  /*
  * wol実行
  */
  public function postWol($id,$jsonList) {
    $result = false;
    if (!empty($jsonList)) {
      foreach($jsonList as $info) {
        $this->logger->info('target=',$info);
        if ($info['id'] == $id) {
          exec($info["command"] . $info["addr"],$output,$result);
          $this->logger->info($result);
          $this->logger->info('result:',$output);
          return $result?false:true;
        }
      }
    }
    return $result;
  }
  /*
  * Ping送信(全体)
  */
  public function pingCmds($jsonList) {
    $results = [];
    foreach($jsonList as $info) {
      $this->logger->info('target=',$info);
      $results[] = $this->pingCmd($info['id'],$info['ipAddr']);
    }
    return $results;
  }
  /*
  * Ping送信
  */
  public function pingCmd($id,$ipAddr) {
    exec('ping -c 2 -t 1 ' . $ipAddr,$output,$result);
    $this->logger->info($result);
    $this->logger->info('result:',$output);
    // コマンドの実行結果 0:正常 0以外:異常
    return ['id' => $id,'result' => $result?false:true];
  }
  /*
  * JSONファイル読み出し
  */
  public function getWolJson() {
    $jsonFile = file_get_contents("/home/meteor/MetHome/work/WakeUpLanApp/config/wol.json");
    $this->json = json_decode($jsonFile,true);
    return $this->json;
  }
  /*
  * JSONファイル読み出し(ID指定)
  */
  public function getWolJsonInfoById($id) {
    $jsonFile = file_get_contents("/home/meteor/MetHome/work/WakeUpLanApp/config/wol.json");
    foreach(json_decode($jsonFile,true) as $info) {
      if ($info['id'] == $id) {
        return $info;
      }
    }
    return null;
  }
}

// test
// $jsonFile = file_get_contents("/home/meteor/MetHome/work/WakeUpLanApp/config/wol.json");
// $json = json_decode($jsonFile,true);
///$test = new WakeUpLanManager();
// $test->postWol(2,$json);
//$test->pingCmd(1,'192.168.1.13');
