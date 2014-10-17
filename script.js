
var max_file_size = Math.pow(1024, 2) * 10

var fileSelector = document.querySelector("#upload")
var fileLabel = document.querySelector("[for='upload']")
var details = document.querySelector("#upload + details")

// | Add a temporary className to an element, expires after `t' miliseconds.
// tempClass :: Elem -> String -> Int -> IO ()
function tempClass(e, c, t) {
    e.className += ' ' + c

    setTimeout(function() {
        e.className = e.className.replace(RegExp(' ' + c, 'g'), "")
    }, t)
}

function upload(e) {
    console.log(this.value)

    fileLabel.className += " loading-background"

    var files = fileSelector.files

    console.log(files)

    var formData = new FormData()

    var total_filesize = 0

    for (var i = 0; i < files.length; i++) {
        console.log(files[i].size + " > " + max_file_size)
        if (files[i].size > max_file_size) {
            tempClass(fileLabel, "error-shake", 500)
            details.className += " error-color"
            details.textContent += "File \"" + files[i].name
                                 + "\" too large, please retry with a smaller "
                                 + "file.\n"
        } else if (total_filesize > max_file_size)
            details.textContent += "Sum of files too large, please retry with "
                                 + "smaller files."

        } else
            formData.append("files[]", files[i], files[i].name)
    }

    var xhr = new XMLHttpRequest()

    xhr.open("POST", "upload.php", true)

    xhr.onload = function() {
        if (xhr.status === 200) success(xhr.responseText)
        else console.log(xhr.status)
    }

    xhr.send(formData)
}

function success() {
    console.log("File uploaded.")

    fileLabel.className =
        fileLabel.className.replace(/ loading-background/g, "")

    tempClass(fileLabel, "success-background", 1000)
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

