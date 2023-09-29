function get_data () {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "https://shibafufes68th.main.jp/api/index.php?k=exhibition_list");
    xhr.send();
    xhr.responseType = "json";
    xhr.onload = () => {
        if (xhr.status == 200) {
            const data = xhr.response;
            console.log(data);
        } else {
            console.log(`Error: ${xhr.status}`);
        }
    };
}

window.addEventListener('DOMContentLoaded', function(){
    setInterval(() => {
        get_data();
    }, 500);
});