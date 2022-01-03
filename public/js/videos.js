// window.onload = () => {
//     let links = document.querySelectorAll("[data-delete]");

//     for (link of links) {
//         link.addEventListener("click", function(e) {
//             e.preventDefault();

//             if (confirm("Êtes-vous sûr de vouloir supprimer cette vidéo ?")) {
//                 fetch(this.getAttribute("href"), {
//                     method: "DELETE",
//                     headers: {
//                         "X-Requested-Width": "XMLHttpRequest",
//                         "Content-type": "application/json"
//                     },
//                     body: JSON.stringify({'_token': this.dataset.token})
//                 }).then(
//                     response => response.json()
//                 ).then(data => {
//                     if (data.success) {
//                         this.parentElement.remove();
//                     } else {
//                         alert(data.error);
//                     }
//                 }).catch(e => alert(e))
//             }
//         });
//     }
// }