const editButtons = document.getElementsByClassName('btn-editar-alumno');

for(let x = 0; x < editButtons.length; x++) {
  editButtons[x].addEventListener('click', function(e) {
    const el = e.srcElement;
    const parent = el.parentElement;
    const idAlumno = parent.dataset.id;

    location.href = '/alta-alumnos?idAlumno=' + idAlumno;
  });
}