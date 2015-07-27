$(function(){
  $('.pc-on').off('click').on('click',function(){
    $.ajax({
        type: 'GET',
        url:'/wol/wakeup/' + $(this).attr('post-id'),
        dataType: 'json'
    }).done(function(data){
      console.log(data);
      alert(data['result']?'起動しました':'起動失敗');
      getPcStatus();
    });
  });
  $('.pc-check').off('click').on('click',function(){
    getPcStatus($(this).attr('check-id'));
  });

  function getPcStatus(id) {
    var url = '/wol/ping/';
    if (id != undefined) {
      url = '/wol/ping/' + id;
    }
    $.ajax({
        type: 'GET',
        url:url,
        dataType: 'json'
    }).done(function(data){
      if (id != undefined) {
        renderPcStatus(id,data);
      } else {
        renderAllPcStatus(data);
      }
    });
  }
  function renderPcStatus(id,json) {
    $('.result').each(function(){
      if ($(this).attr('result-id') == id) {
        if (json['result']) {
          $(this).css('color','blue');
          $(this).text('起動中');
        } else {
          $(this).css('color','red');
          $(this).text('停止中');
        }
        return false;
      }
    });
  }
  function renderAllPcStatus(data) {
    for(index in data) {
      console.log(data[index]);
      renderPcStatus(data[index]['id'],data[index]);
    }
  }
  getPcStatus();
});
