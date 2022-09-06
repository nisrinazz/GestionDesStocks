window.onload = () => {
  const FiltersForm = document.querySelector('#filters');

  document.querySelectorAll('#filters input').forEach(input =>
  { input.addEventListener("change", () =>{
  const Form = new FormData(FiltersForm);
  const Params = new URLSearchParams();
  Form.forEach((value , key)=>{
    Params.append(value,key);
    console.log(1);
    })
  })
  });
  // const Url = new URL(window.location.href);
  //
  // fetch(Url.pathname + "?" +  Params.toString() , {
  //   headers : {
  //     "X-Requested-With" : "XMLHttpRequest"
  //   }
  // }).then(response => {console.log(response)
  // }).catch( e => alert(e));
}
