// alert(1);

  
// const articles = document.getElementById('articles');

// console.log(articles);

window.onload = function() {
    const articles = document.getElementById('articles');
    // console.log(articles);
    if (articles) {
        articles.addEventListener('click', e => {
          if (e.target.className === 'bg-red-700 hover:bg-red-900 text-white font-bold py-2 px-4 rounded-full') {
            if (confirm('Are you sure?')) {
                const id = e.target.getAttribute('data-id');
                // alert(id);
                fetch(`/article/delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
          }
        });
    }
}
