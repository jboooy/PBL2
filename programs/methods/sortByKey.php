<?php
  // 指定したキーに対応する値を基準に、配列をソートする
  //$key_nameでキーを指定（スコア基準なら'point'）
  //$sort_order(降順：SORT_DESC)
  //$arrayにソートしたい配列
  function sortByKey($key_name, $sort_order, $array) {
    foreach ($array as $key => $value) {
        $standard_key_array[$key] = $value[$key_name];
    }
    array_multisort($standard_key_array, $sort_order, $array);
    return $array;
  }
?>
