<? php 
	
function DeleteAlumnoFromResult($listado, $id) {
	if(in_array($id, $listado)) {
		if (($key = array_search($id, $listado)) !== false) {
		    unset($listado[$key]);
		} else {
			print("error1 ")
		}
	} else {
		print("error2 ")	
	}

	return $listado;
}

?>