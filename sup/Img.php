<?php
class Img {
  private $path;
  function __construct($path)
  {
    $this->path = $path;
  }
  function setPath($path) {
    if(file_exists($path)){
      $this->path = $path;
      return true;
    } else {
      return false;
    }
  }
  function delete($name) {
    if (!$name) {
      return;
    }
    $name = basename($name);
    $is_exists = file_exists($this->path.$name);
    if ($is_exists) {
      @unlink($this->path.$name);
    }
  }
  function storeUpload($preName, $stmt) {
    $re = '/.*\.(.+)/m';
    preg_match($re, $preName, $matches);
    $ext = $matches[1];

    $name = uniqid().".".$ext;
    move_uploaded_file($stmt, $this->path.$name);
    return $name;
  }
}
