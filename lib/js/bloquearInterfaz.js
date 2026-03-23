function blockUI() {
  document.body.insertAdjacentHTML(
    "beforeend",
    `<div id="loader"
      style="
        position:fixed;
        inset:0;
        background:rgba(0,0,0,.5);
        display:flex;
        align-items:center;
        justify-content:center;
        color:white;
        font-size:1.5rem;
        z-index:9999;">
        Procesando...
     </div>`
  );
}

function unblockUI() {
  document.getElementById("loader")?.remove();
}