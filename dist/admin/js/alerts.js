const deletecompany = (id) => {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, lets do it!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.get(
          "operations.php",
          { operation: "deletecompany", id: id },
          function (response) {
            if (response.trim().normalize() == "ok".trim().normalize()) {
              //another alert saying sccess
              Swal.fire({
                    icon: "success",
                    title: "Successfully deleted",
                    showConfirmButton: false,
                    timer: 1500,
                    buttons: false,
                })
                .then(() => {
                    location.reload()
                })
              // TODO
              //perform UI update if you are using ajax
              //or redirect
            } 
            else {
              //console.log(response);
              Swal.fire("Unable to delete. Error: " + response);
            }
          }
        );
      }
    });
};