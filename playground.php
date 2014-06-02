function valid_date($cpr){
  if (preg_match('/(\d{2})(\d{2})(\d{2})-?(\d{1})(\d{2})(\d{1})/', $cpr, $match)) {
    $day        = $match[1];
    $month      = $match[2];
    $year       = $match[3];
    $year_check = $match[4];
    $serial     = $match[5];
    $control    = $match[6];
    
    $prefix_year = '';
	  switch($year_check){
	    case 0:
	    case 1:
	    case 2:
	    case 3:
	      //1900-1999
	      $prefix_year = 19;
	      break;
	    case 4:
	      //2000-2036 eller 1937-1999
	      if($year > 36)
	        $prefix_year = 19;
	      else
	        $prefix_year = 20;
	      break;
	    case 5:
	    case 6:
	    case 7:
	    case 8:
	      //2000-2057, 1858-1899
	      if($year > 57)
	        $prefix_year = 18;
	      else
	        $prefix_year = 20;
	      break;
	    case 9:
	      //2000-2036, 1937-1999
	      if($year > 37)
	        $prefix_year = 19;
	      else
	        $prefix_year = 20;
	      break;
	    default:
	      return false;
	  }
	  $year = sprintf("%d%d", $prefix_year, $year);
	  return checkdate($month, $day, $year);
  } else {
	  return false;
  }
}

function modulus11($cpr){
  $number = str_replace('-', '', $cpr);
  $factor = '4327654321';
  $number_arr = str_split($number);
  $factor_arr = str_split($factor);
  $sum = 0;
  for ($i = 0; $i < count($factor_arr); $i++) {
    $sum += $number_arr[$i] * $factor_arr[$i];
  }
  $check = $sum % 11;
  return ($check == 0) ? true : false;
}
