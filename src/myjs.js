

function S(selector) {
    const elements = document.querySelectorAll(selector);
    if (elements.length === 1) {
        return elements[0];
    } else {
        return elements;
    }
}

function passwordVisiblity(input, button) {
    const password = S(input);
    const toggle = S(button);
    if (password.type === 'password') {
        password.type = 'text';
        toggle.value = options['lang']['hide'];
    } else {
        password.type = 'password';
        toggle.value = options['lang']['show'];
    }
}

function show(display, id)//show and hide elements, display letter f for flex element and b for block id for the element to show or hide
{
    element = S('#'+ id);
    hide = 'hide' + display;
    seen = 'show' + display;
    if(mhasClass(element, hide))
    {
        mremoveClass(element, hide);
        maddClass(element, seen);
    } else {
        mremoveClass(element, seen);
        maddClass(element, hide);
    }
}


function maddClass(elements, className)
{
    if (!Array.isArray(elements))
    {
        elements = [elements];
    }
    for (var i = 0; i < elements.length; i++)
    {
        elements[i].classList.add(className);
    }
}

function mremoveClass(elements, className)
{
    if (!Array.isArray(elements))
    {
        elements = [elements];
    }
    for (var i = 0; i < elements.length; i++)
    {
        elements[i].classList.remove(className);
    }
}

function mhasClass(element, className)
{
    return element.classList.contains(className);
}

document.addEventListener('click', function(event) {
});