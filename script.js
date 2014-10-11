
var fileSelector = document.querySelector("#upload")

function upload(e) {
    console.log(this.value)

    var files = fileSelector.files

    var formData = new FormData()

    for (var i = 0; i < files.length; i++)
        formData.append("files[]", files[i], files[i].name)

    var xhr = new XMLHttpRequest()

    xhr.open("POST", "upload.php", true)

    xhr.onload = function() {
        if (xhr.status === 200) success()
    }

    xhr.send(formData)
}

function success() {
    console.log("File uploaded.")
}

function events() {
    fileSelector.addEventListener("click", function(e) {
        this.value = null
    })

    fileSelector.addEventListener("change", upload)
}

function main() {
    events()
}

main()

