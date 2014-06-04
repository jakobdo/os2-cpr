<?php

class CPR{
  private $cpr        = NULL;
  private $day        = NULL;
  private $month      = NULL;
  private $year       = NULL;
  private $year_check = NULL;
  private $serial     = NULL;
  private $control    = NULL;
  
  public function __construct($cpr){
    if (preg_match('/^(0[1-9]|[12][0-9]|3[01])(0[1-9]|1[012])([\d]{2})[\s-]?(\d{1})(\d{2})(\d{1})$/', $cpr, $matches)) {
      list($skip, $this->day, $this->month, $this->year, $this->year_check, $this->serial, $this->control) = $matches;
      $this->cpr = implode('', array_slice($matches, 1, 6));
    }else{
      throw new Exception("Invalid CPR, 1234567890 or 123456-7890 accepted");
    }
  }
  
  public function valid_date(){
    switch($this->year_check){
	    case 0:
	    case 1:
	    case 2:
	    case 3:
	      //1900-1999
	      $prefix_year = 19;
	      break;
	    case 4:
	      //2000-2036 eller 1937-1999
	      if($this->year > 36)
	        $prefix_year = 19;
	      else
	        $prefix_year = 20;
	      break;
	    case 5:
	    case 6:
	    case 7:
	    case 8:
	      //2000-2057, 1858-1899
	      if($this->year > 57)
	        $prefix_year = 18;
	      else
	        $prefix_year = 20;
	      break;
	    case 9:
	      //2000-2036, 1937-1999
	      if($this->year > 37)
	        $prefix_year = 19;
	      else
	        $prefix_year = 20;
	      break;
	    default:
	      return false;
	  }
	  $this->year = sprintf("%d%d", $prefix_year, $this->year);
	  return checkdate($this->month, $this->day, $this->year);
  }
  
  public function modulus11(){
    $factor = '4327654321';
    $number_arr = str_split($this->cpr);
    $factor_arr = str_split($factor);
    $sum = 0;
    for ($i = 0; $i < count($factor_arr); $i++) {
      $sum += $number_arr[$i] * $factor_arr[$i];
    }
    return (($sum % 11) == 0) ? true : false;
  }
}

if (basename($argv[0]) == basename(__FILE__)){
  echo '<pre>';
  try {
    $cpr = isset($_GET['cpr']) ? $_GET['cpr'] : '';
    $c = new CPR($cpr);
    var_dump($c->valid_date());
    var_dump($c->modulus11());
  } catch (Exception $e) {
    printf("Exception: %s", $e->getMessage());
  }
  echo '</pre>';
}
