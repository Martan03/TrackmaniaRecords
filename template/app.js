var url;
function show_remove(url)
{
    this.url = url;
    var remove = document.querySelector(".remove");
    remove.style.display = "flex";
}

function remove()
{
    window.location.href = url;
}

function hide_remove()
{
    var remove = document.querySelector(".remove");
    remove.style.display = "none";
}