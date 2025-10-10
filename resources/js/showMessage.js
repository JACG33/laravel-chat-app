/**
 * Funcion para mostar un mensaje
 * @param {Object} options - Objecto de Opciones.
 * @param {String} options.message - Mensaje a mostar.
 * @param {String} options.color - Color del Mesanje, opciones de color (red, blue, orange, green).
 * @returns {String} Html del mensaje encapsulado
 */
export const showMessage = ({ message = "", color = "green" }) => {
  const randomnumber = Math.ceil(Math.random() * 999999);
  const idDiv = `div-${randomnumber}`;
  const idBtn = `btn-${randomnumber}`;
  let time = 10;

  const styles = `
    <style>
      .message__area {
        position: fixed;
        inset: auto 5px 5px auto;
        display: grid;
        gap: .5rem;
        color:#fff;
        z-index:999;
        font-size:0.9rem;
      }

      .msg__item--green{background-color: #3bba1f; }
      .msg__item--red{background-color: #ba1f1f; }
      .msg__item--blue{background-color: #1f5cba; }
      .msg__item--orange{background-color: #ba671f; }

      .message__area .msg__item {
        position: relative;
        overflow: hidden;
        padding: 1rem; 
        border-radius: .3rem; 
        display:flex; 
        justify-content: space-between; 
        align-items:center; 
        width: 100%;
        max-width: 340px;
        min-height: 70px; 
        gap: .5rem;
        animation: fade 250ms linear both;
    }

      .message__area .out{animation: fadeout 250ms linear both;}

      @keyframes fade{from{translate: 100vw 0vw;}to{translate: 0vw 0vw;}}
      
      @keyframes fadeout{from{translate: 0vw 0vw;}to{translate: 100vw 0vw;}}

      .message__area .msg__close {
        cursor: pointer;
        position: relative;
        z-index: 1;
        display: flex;
        place-content: center;
        border: none;
        padding: 0px;
        margin: 0px;
        background-color: transparent;
        color: #fff;
      }

      .message__area .timer{
        position: absolute;
        width: 100%;
        height: 10px;
        background: yellow;
        inset: auto 0px 0px;
        transition:width 1s ease-in-out;
        border-radius: .2rem; 
      }

    </style>
  `;

  let timerBar = 100;
  const cleartime = setInterval(() => {
    time--;
    document
      .getElementById(idDiv)
      .querySelector(".timer").style.width = `${(timerBar -=
        timerBar / time)}%`;
    if (time <= 0) {
      document.getElementById(idDiv)?.classList.add("out");
      setTimeout(() => {
        document.getElementById(idDiv)?.remove();
        clearInterval(cleartime);
      }, 250);
    }
  }, 1000);

  document.addEventListener("click", (e) => {
    const { target } = e;
    if (target.closest(`[data-btn="${idBtn}"]`)) {
      target.closest(`#${idDiv}`).remove();
      clearInterval(cleartime);
    }
  });
  document.removeEventListener("click", (e) => { });

  const $body = document.querySelector("body");
  let $messageArea = $body.querySelector("#messageArea");
  if (!$body.querySelector("#messageArea")) {
    document.querySelector("head").insertAdjacentHTML("beforeend", styles);
    $body.insertAdjacentHTML(
      "afterbegin",
      ` <div class="message__area" id="messageArea">`
    );
    $messageArea = $body.querySelector("#messageArea");
  }
  $messageArea.insertAdjacentHTML(
    "afterbegin",
    `
      <div id="${idDiv}" class="msg__item msg__item--${color} ${idDiv}">
        <div>
          <span>${message}</span>
        </div>
        <div>
          <button type="button" class="msg__close" data-btn="${idBtn}"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-x" width="30" height="30" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M10 10l4 4m0 -4l-4 4" /></svg></button>
        </div>
        <div class="timer"></div>
      </div>
    `
  );
};
