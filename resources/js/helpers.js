/**
 * Funcion para cortar texto.
 * @param {string} texto Texto a cortar.
 * @param {number} numeroCaracteres Numero de caracteres a cortar.
 * @returns Texto cortado.
 */
export const cutText = (texto, numeroCaracteres) =>
  texto.slice(texto, numeroCaracteres);


/**
 * funcion que genera un color rgb
 * @param {string} name nombre del usuario
 */
export function generateColor(name) {
  let color1 = name.length * 7;
  let color2 = name.length * 12;
  let color3 = name.length * 18;

  if (color1 > 255)
    color1 = color1 - 253;

  if (color2 > 255)
    color2 = color2 - 245;

  if (color3 > 255)
    color3 = color3 - 225;

  return `rgb(${color1} ,${color2} ,${color3})`;
}