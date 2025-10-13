import { cutText, generateColor } from "./helpers.js";
import { DocIcon, PdfIcon, PptIcon, XlsIcon } from "./icons.js";
import { showMessage } from "./showMessage.js";
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('messageForm');
  const messages = document.getElementById('messages');
  const id_chat = document.getElementById('chat');
  const userlogged = document.getElementById('userlogged').value;
  const targetTopChatMssg = document.getElementById('top_chat_messages')
  let nextUrl = document.querySelector('[rel=next]')?.href
  let files = []

  const options = {
    root: messages, // Default: the viewport
    rootMargin: '0px', // No margin around the root
    threshold: 1 // Trigger when 50% of the target is visible
  };
  const obeserver = new IntersectionObserver(handleIntersection, options)

  setTimeout(() => {
    messages.scrollTop = messages.scrollHeight
  }, 0);

  setTimeout(() => {
    obeserver.observe(targetTopChatMssg)
  }, 500);

  // Listen for messages
  window.Echo.channel('chat')
    .listen('MessageSent', (msj) => {
      console.log(msj)


      const messageElement = generateMessageBubble({
        date: msj.date,
        files: msj.files,
        message: msj.message,
        time: msj.time,
        user_id: msj.user_id,
        user_name: msj.user_name
      })

      messages.appendChild(messageElement);
      messages.scrollTop = messages.scrollHeight
    });


  // Handle form submission
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    document.querySelector("#btn_send_message").setAttribute('disabled', true)
    if (window.textEditor.getData() == '' && files.length < 1) {
      showMessage({ color: 'orange', message: 'No puede enviar un mensaje vacio, se debe escribir un mensaje o seleccionar un archivo.' })
      document.querySelector("#btn_send_message").removeAttribute('disabled')
      return
    }


    const form = new FormData
    if (files.length > 0 && window.textEditor.getData() == '')
      form.append('message', '...')
    else
      form.append('message', window.textEditor.getData())
    form.append('id_chat', id_chat.value)
    if (files.length > 0)
      files.forEach(element => {
        form.append('file[]', element)
      });

    try {
      const req = await fetch(`/chat/send/${id_chat.value}`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: form
      })

      if (!req.ok) throw await req.json();

      window.textEditor.setData('')
      document.querySelector('#chat_file').value = null
      document.querySelector('#preview_files_area').innerHTML = null
      files = []
      document.querySelector('#chat_count_files').classList.add('hidden')
      document.querySelector('#chat_count_files').textContent = null
    } catch (error) {
      console.log(error);
    }
    document.querySelector("#btn_send_message").removeAttribute('disabled')
  });

  // Handle Click
  document.addEventListener("click", e => {
    const {
      target
    } = e

    if (target.closest("[id=btn_chat_file]")) {
      target.closest("[id=btn_chat_file]").querySelector("input").click()
      document.querySelector('#modal_chat_files').showModal()
    }

    if (target.closest("[id=btn_close_chat_file]")) {
      document.querySelector('#modal_chat_files').close()
    }

    if (target.closest("[id=btn_add_chat_file]")) {
      document.querySelector("#btn_chat_file").click()
    }

    if (target.closest("[data-btn=preview-file]")) {
      const name = target.closest("[data-btn=preview-file]").dataset.previewfile
      files = files.filter(ele => ele.name != name)
      target.closest("[data-btn=preview-file]").parentElement.remove()

      if (files.length < 1) {
        document.querySelector('#chat_count_files').classList.add('hidden')
        document.querySelector('#chat_count_files').textContent = null
      } else
        document.querySelector('#chat_count_files').textContent = files.length

      showMessage({ color: 'green', message: 'Se ha removido el archivo' })
    }
  })

  // Handle Change
  document.addEventListener('change', e => {
    const {
      target
    } = e

    if (target.id == 'chat_file') {
      if (target.files[0] != null) {
        const findFile = files.find(ele => ele.name == target.files[0].name)
        if (findFile) {
          showMessage({ color: 'orange', message: 'Ya se ha cargado un archivo con ese nombre' })
          target.value = null
          return
        }
        files.push(target.files[0])

        console.log(files)


        loadPriviewFile({
          file: target.files[0],
          area_to_preview: 'preview_files_area'
        })
        target.value = null
        document.querySelector('#chat_count_files').classList.remove('hidden')
        document.querySelector('#chat_count_files').textContent = files.length
      }
    }
  })


  /**
   * Funcion que genera la preview del archivo a subir
   * @param {object} params objeto de parametro.
   * @param {HTMLInputElement.files} params.file archivo a procesar.
   * @param {string} params.area_to_preview lugar donde se mostrara la preview
   */
  function loadPriviewFile({
    file,
    area_to_preview
  }) {
    const fileExtension = file.name.split(".").slice(-1,)
    const fileName = `${cutText(file.name, 15)}.${fileExtension}`;
    // Wrapper Item
    const div = document.createElement("div")
    div.className = "flex flex-col gap-1 relative"

    const clearButton = document.createElement('button')
    clearButton.className = 'absolute top-0 right-0 p-1 bg-red-500 flex justify-center items-center text-white cursor-pointer rounded-sm'
    clearButton.dataset.btn = 'preview-file'
    clearButton.dataset.previewfile = file.name
    clearButton.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>`

    div.append(clearButton)

    // Text Item
    const span = document.createElement("span")
    span.textContent = fileName

    div.append(span)

    // Image file
    if (file.type.includes('image')) {
      let tmpurl = URL.createObjectURL(file)
      const img = document.createElement('img')
      img.width = '90'
      img.height = '90'
      img.className = "rounded-md object-cover h-40 w-40"
      img.src = tmpurl
      div.insertAdjacentElement("afterbegin", img)
    }

    // Pdf file
    if (file.type.includes('pdf')) {
      const svg = PdfIcon({ height: 160, width: 160 })
      div.insertAdjacentHTML("afterbegin", svg)
    }

    // Doc file
    if (file.name.includes('doc') || file.name.includes('docx')) {
      const svg = DocIcon({ height: 160, width: 160 })
      div.insertAdjacentHTML("afterbegin", svg)
    }

    // Excel file
    if (file.name.includes('xls') || file.name.includes('xlsx')) {
      const svg = XlsIcon({ height: 160, width: 160 })
      div.insertAdjacentHTML("afterbegin", svg)
    }

    document.querySelector(`#${area_to_preview}`).append(div)
  }



  /**
   * Funcion que obtiene los mensajes anteriores del chat
   * @param {HTMLElement} loader Contenedor del spinner
   */
  function getOldMessages(loader) {
    if (nextUrl == null) {
      loader.classList.remove('visible');
      loader.querySelector("#spinner").classList.remove('animate-spin')
      return
    }
    fetch(nextUrl, {
      headers: {
        'x-type': 'message',
        'X-Requested-With': 'XMLHttpRequest'
      }
    }).then(res => res.json()).then(res => {
      const tmps = new DocumentFragment()
      let fecha = {}

      res.data.reverse().forEach(data => {

        nextUrl = res?.next_page_url
        let tmptime = generateDateToPill(data.date)

        if (!fecha[tmptime]) {
          fecha[tmptime] = tmptime
          const pill1 = document.createElement('div')
          pill1.className = 'flex justify-center items-center m-auto w-fit text-[10px] bg-gray-300 border-2 border-gray-200/40 text-zinc-950 px-2 py-1 rounded-2xl  z-50 sticky inset-[0px_0px_auto_0px]'
          pill1.dataset.pilltime = tmptime

          if (document.querySelector(`[data-pilltime='${tmptime}']`))
            document.querySelector(`[data-pilltime='${tmptime}']`).remove()

          pill1.innerHTML = `<span>${tmptime}</span>`
          tmps.appendChild(pill1)
        }

        tmps.appendChild(generateMessageBubble({
          date: data.date,
          files: data.files,
          message: data.message,
          time: data.time,
          user_id: data.id_usuario,
          user_name: data.get_user.name
        }))

      })
      targetTopChatMssg.after(tmps)
      messages.scrollTop = '80'
    }).finally(res => {
      loader.classList.remove('visible');
      loader.querySelector("#spinner").classList.remove('animate-spin')
    })
  }

  function handleIntersection(entries, observer) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        entry.target.querySelector("#spinner").classList.add('animate-spin')
        getOldMessages(entry.target)
      }
    });
  };


  function generateSvg(name) {
    let svg = ``
    // Pdf file
    if (name.includes('pdf')) {
      svg = PdfIcon({ height: 160, width: 160 })
    }

    // Doc file
    if (name.includes('doc') || name.includes('docx')) {
      svg = DocIcon({ height: 160, width: 160 })
    }

    // Excel file
    if (name.includes('xls') || name.includes('xlsx')) {
      svg = XlsIcon({ height: 160, width: 160 })
    }
    return svg
  }

  /**
   * Funcion que crea la burbuja del mensaje con los parametros solicitads
   * @param {object} param Objecto de parametrod
   * @param {number} param.user_id Id del usuario
   * @param {string} param.user_name Nombre de usuario
   * @param {string} param.message Mensaje
   * @param {string} [param.files=''] Archivos
   * @param {string} param.time Fecha de creacion del mensaje
   * @returns HTMLDivElement
   */
  function generateMessageBubble({
    user_id,
    user_name,
    message,
    files,
    time,
  }) {

    const messageWrapper = document.createElement('div');
    if (userlogged != user_id)
      // left message
      messageWrapper.className = 'grid grid-cols-[40px_1fr] gap-2 justify-start items-end'
    else
      // right message
      messageWrapper.className = 'mr-1.5 grid gap-2 justify-end items-end'


    if (userlogged != user_id) {
      const imgUser = document.createElement('div')
      imgUser.className = 'w-10 h-10 rounded-full p-1 flex justify-center items-center'
      imgUser.textContent = user_name[0]
      imgUser.style.backgroundColor = generateColor(user_name)
      messageWrapper.append(imgUser)
    }


    const messageContent = document.createElement('div')
    if (userlogged != user_id) {
      // left message
      messageContent.className = 'grid gap-1 bg-zinc-600 rounded-tr-md rounded-br-md rounded-tl-md p-2 w-full max-w-3xl'
      const spanUserName = document.createElement('span')
      spanUserName.className = 'text-[12px]'
      spanUserName.textContent = user_name
      messageContent.append(spanUserName)
    } else
      // right message
      messageContent.className = 'grid gap-1 bg-blue-600 rounded-tr-md rounded-bl-md rounded-tl-md p-2 w-full max-w-3xl'

    if (files) {
      const divfiles = document.createElement('div')
      divfiles.className = 'flex flex-wrap gap-1 relative'
      const message_files = files.split(',')

      message_files.forEach((ele) => {
        const exte = ele.split('.')
        const name_file = ele.split('/')
        if (exte[exte.length - 1] == 'png' || exte[exte.length - 1] == 'jpg') {
          divfiles.innerHTML += `
            <img loading="lazy" class="rounded-md object-cover h-40 w-40" width="90" height="90" src="/storage/${ele}" alt="" srcset="">
          `
        } else {
          divfiles.innerHTML += `
            <a href="/storage/${ele}" class="flex flex-col gap-1 relative">
              ${generateSvg(ele)}
              ${cutText(name_file[name_file.length - 1], 10)}.${exte[exte.length - 1]}
            </a>
          `
        }
      })

      messageContent.append(divfiles)
    }

    const isoString = time;
    const date_time = new Date(isoString);

    const formattedTime = new Intl.DateTimeFormat('en-VE', {
      hour: '2-digit',
      minute: '2-digit',
      hour12: true,

    }).format(date_time);

    let tmp = `
      <div>${message}</div>
      <div class="flex justify-end items-center">
        <small class="text-[12px]">${formattedTime}</small>
      </div>
    `

    messageContent.innerHTML += tmp
    messageWrapper.append(messageContent)

    return messageWrapper

  }

  function generateDateToPill(dateTime) {
    const isoString = dateTime;
    const date_time = new Date(isoString);

    return new Intl.DateTimeFormat('es-VE', {
      dateStyle: 'medium',
    }).format(date_time);
  }

})