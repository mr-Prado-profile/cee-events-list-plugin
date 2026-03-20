
document.addEventListener("DOMContentLoaded",()=>{

    const search=document.querySelector("#cee-search");
    const category=document.querySelector("#cee-category");
    const results=document.querySelector("#cee-results");
    const pagination=document.querySelector("#cee-pagination");
    const tabs=document.querySelectorAll(".cee-tab");

    let currentPage = 1;

    function loadEvents(page = 1){
        currentPage = page;
        const formData=new FormData();
        formData.append("action","cee_filter");
        formData.append("keyword",search ? search.value : "");
        formData.append("category",category ? category.value : "");
        formData.append("paged",currentPage);

        fetch(cee_ajax.ajax_url,{method:"POST",body:formData})
        .then(res=>{
            if(!res.ok) throw new Error("Network response was not ok");
            return res.json();
        })
        .then(res=>{
            if(res.success){
                results.innerHTML=res.data.html;
                renderPagination(res.data.pagination);
                attachModalEvents();
            } else {
                results.innerHTML = "<p>Error loading events.</p>";
            }
        })
        .catch(err => {
            console.error("CEE Events Error:", err);
            results.innerHTML = "<p>Failed to connect to the server.</p>";
        });
    }

    function renderPagination(html_content){
        pagination.innerHTML = "";
        if(!html_content) return;

        let html = '<div class="cee-pagination">' + html_content + '</div>';
        pagination.innerHTML = html;

        pagination.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", (e)=>{
                e.preventDefault();
                const href = e.currentTarget.getAttribute('href');
                if (href) {
                    const pageMatch = href.match(/paged=(\d+)/);
                    const page = pageMatch ? pageMatch[1] : 1;
                    loadEvents(page);
                    window.scrollTo({
                        top: document.querySelector(".cee-main").offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Modal Logic
    const modal = document.getElementById("cee-reg-modal");
    
    function attachModalEvents() {
        document.querySelectorAll(".cee-open-modal").forEach(btn => {
            btn.onclick = function() {
                if(modal) modal.style.display = "block";
            }
        });
    }

    if(modal) {
        const closeBtn = modal.querySelector(".cee-modal-close");
        if(closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = "none";
            }
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }

    if(search){ search.addEventListener("input",()=>{ loadEvents(1); }); }
    if(category){ category.addEventListener("change",()=>{ loadEvents(1); }); }

    loadEvents();
    attachModalEvents();
});
