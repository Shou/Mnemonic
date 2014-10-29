
// {{{ Constants

var max_file_size = Math.pow(1024, 2) * 10

var fileSelector = document.querySelector("#upload")
var fileLabel = document.querySelector("[for='upload']")
var details = document.querySelector("#upload + details")
var login = document.querySelector("#login")
var register = document.querySelector("#register")

// }}}

// {{{ Utility functions

// | Add a temporary className to an element, expires after `t' miliseconds.
// tempClass :: Elem -> String -> Int -> IO ()
function tempClass(e, c, t) {
    e.className += ' ' + c

    setTimeout(function() {
        removeClass(e, c)
    }, t)
}

// | Remove a class from an element's className.
// removeClass :: Elem -> String -> IO ()
function removeClass(e, c) {
    e.className = e.className.replace(RegExp(' ' + c, 'g'), "")
}

// | Make an <input> element.
// input :: String -> String -> String -> String -> IO Elem
function input(type, name, value, label) {
    var inp = document.createElement("input")

    inp.type = type
    inp.name = name
    inp.value = value
    inp.placeholder = label

    return inp
}

function 

// }}}

// | Upload a file to the server.
// upload :: Event -> IO ()
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
        } else if (total_filesize > max_file_size) {
            details.textContent += "Sum of files too large, please retry with "
                                 + "smaller files."

        } else
            formData.append("files[]", files[i], files[i].name)
    }

    var xhr = new XMLHttpRequest()

    xhr.open("POST", "/upload/", true)

    xhr.onload = function() {
        if (xhr.status === 200) success(xhr.responseText)
        else failure()
    }

    xhr.send(formData)
}

// success :: String -> IO ()
function success(rtxt) {
    console.log("File uploaded.")

    fileLabel.className =
        fileLabel.className.replace(/ loading-background/g, "")

    tempClass(fileLabel, "success-background", 1000)
}

// failure :: IO ()
function failure() {
    console.log("File upload failure.")

    removeClass(fileLabel, "loading-background")
    tempClass(fileLabel, "error-shake", 1000)

    details.textContent = "Server error."
}

// events :: IO ()
function events() {
    // File selector event
    fileSelector.addEventListener("click", function(e) {
        this.value = null
    })

    fileSelector.addEventListener("change", upload)

    // Register event
    register.addEventListener("click", function(e) {
        var xhr = new XMLHttpRequest()

        xhr.open("POST", "/register/", true)

        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
        xhr.setRequestHeader("Connection", "close")

        xhr.onload = function() {
            if (xhr.status === 200) console.log(xhr.responseText)
            else console.log("Registration failed.")
        }

        xhr.send("nick=root&pass=admin")
    })

    // Login event
    login.addEventListener("click", function(e) {
    })
}


function main() {
    events()
}

main()

