
export function removeBeforeChar(str, char) {
    let index = str.indexOf(char);
    if (index !== -1) {
        return str.slice(index + 1);
    }
    return str;
}