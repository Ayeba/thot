<?php


class myfpdi extends fpdi {

	public $addPageNum = 0;
	
	
public function Footer()
{
    // Positionnement ˆ 1,5 cm du bas
    $this->SetY(-15);
    // Police Arial italique 8
    $this->SetFont('Arial','I',8);
    // NumŽro de page
    if ($this->addPageNum == 1) {
    	$page = $this->PageNo() - 2;
    	if ($page % 2)
    		$pos = 'R';
    	else 
    		$pos = 'L';
	    $this->Cell(0,10,$page,0,0,$pos);
	    //$this->Cell(0,10,$page .'/{nb}',0,0,'C');
	    
    }
}

}