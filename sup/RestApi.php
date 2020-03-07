<?php
// Restful
// @method setGet 
// @method setPost
// @method setDelete
//   @param func
//   @return $this
// @method receiveReq
//   @param str: request-method
//   @return func
class Restful
{
  private function defaultReturn()
  {
    $msg = [
      'success' => false,
      'msg' => 'req method not allow'
    ];
    echo json_encode($msg);
  }
  private function getFunc()
  {
    $this->defaultReturn();
  }
  private function postFunc()
  {
    $this->defaultReturn();
  }
  private function deleteFunc()
  {
    $this->defaultReturn();
  }
  function setGet($func)
  {
    $this->getFunc = $func;
    return $this;
  }
  function setPost($func)
  {
    $this->postFunc = $func;
    return $this;
  }
  function setDelete($func)
  {
    $this->deleteFunc = $func;
    return $this;
  }
  function receiveReq($reqMethod)
  {
    if ($reqMethod === 'GET') {
      return $this->getFunc;
    } else if ($reqMethod === 'POST') {
      return $this->postFunc;
    } elseif ($reqMethod === 'DELETE') {
      return $this->deleteFunc;
    } else {
      $this->defaultReturn();
    }
  }
}
