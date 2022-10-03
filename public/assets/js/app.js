function togglemenu() {
  let toggle = document.querySelector(".toggle");
  let navigation = document.querySelector(".navigation");
  let main = document.querySelector(".main");
  toggle.classList.toggle("active");
  navigation.classList.toggle("active");
  main.classList.toggle("active");
}

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

function showMessage(message)
{
  Toast.fire({
      icon: 'success',
      title: message
    })
  
}

function deleteEntity(myUrl)
{
  var url = myUrl
  var urlSplit = url.split('/');
  var redirect = `${urlSplit[0]}s/`
  
  console.log("clicked");
  Swal.fire({
    title: 'Voulez-vous Vraiment ',
    text: "supprimer cette enregustrement",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, Supprimer'
  }).then((result) => {
    if (result.isConfirmed) {
      console.log(url);
      $.ajax({
        url : url,
        method: "GET",
        dataType : 'json'
      })
      .done(function(response){
        Swal.fire(
          'Supprimer!',
          JSON.stringify(response),
          'success'
        )
        window.location.href = redirect;
      })
      .fail(function(error){
        Swal.fire(
          'Erreur!',
          'Impossible de supprimer cette enregistrement',
          'error'
        )
        console.error(JSON.stringify(error));
      })
    }
  })
}