function openNav() {
    let sbcb = document.getElementById("sbcb");
    sbcb.checked = true;
    let content = document.getElementById("content");
    content.classList.add('darkened');
}
function closeNav() {
    let sbcb = document.getElementById("sbcb");
    sbcb.checked = false;
    let content = document.getElementById("content");
    content.classList.remove('darkened');
}
function expandDropdownConent(checkbox) {
    let dropdown = checkbox.nextElementSibling.nextElementSibling;
    if (checkbox.checked) {
        dropdown.style.maxHeight = dropdown.scrollHeight + "px";
    } else {
        dropdown.style.maxHeight = 0 + "px";
    }
}