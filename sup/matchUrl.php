<?php
// matchImgUrl
// @param  str: HTML-formate
// @return array / imgURL
function matchImgUrl($str)
{
  $re = '/<img src="(.+)">/mU';
  preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
  $imgNames = [];
  foreach ($matches as $match) {
    $imgNames[] = $match[1];
  }
  return $imgNames;
}