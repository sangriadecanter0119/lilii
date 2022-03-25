<?php

class AppController extends Controller
{
  public $components = array('Security');

  public function beforeFilter() {
    parent::beforeFilter();
    // http���N�G�X�g�̂Ƃ��Ɏ��s���郁�\�b�h
    //$this->Security->blackHoleCallback = 'forceSecure';
    // https�������������A�N�V����
    // requireSecure���\�b�h�Ɉ������Ȃ��ꍇ�͑S�ẴA�N�V������https����������
    //$this->Security->requireSecure();
  }

  public function forceSecure() {
    $this->redirect("http://".env('SERVER_NAME').$this->here);
  }
}

?>