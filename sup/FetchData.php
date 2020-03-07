<?php
class FetchData
{
  function getData()
  {
    $data = [];
    foreach ($this as $key => $value) {
      if ($key === 'getData') {
        continue;
      } else {
        $data[$key] = $value;
        // $data[urlencode($key)] = urlencode($value);
      }
    }
    return $data;
  }
}