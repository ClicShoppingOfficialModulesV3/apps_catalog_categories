<?php
/**
 * CategoryCommon.php
 * @copyright Copyright 2008 - http://www.innov-concept.com
 * @Brand : ClicShopping(Tm) at Inpi all right Reserved
 * @license GPL 2 License & MIT Licencse
 
 */

  namespace ClicShopping\Apps\Catalog\Categories\Classes\Common;


  class CategoryCommon {

/*
* Parse and secure the cPath parameter values
* @int, $cPath, value of cpath
* return @ string array $tmp_array
* osc_parse_category_path
*/
    public function getParseCategoryPath($cPath) {
// make sure the category IDs are integers
      $cPath_array = array_map(function ($string) {
        return (int)$string;
      }, explode('_', $cPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
      $tmp_array = [];
      $n = count($cPath_array);
      for ($i=0; $i<$n; $i++) {
        if (!in_array($cPath_array[$i], $tmp_array)) {
          $tmp_array[] = $cPath_array[$i];
        }
      }

      return $tmp_array;
    }
  }
