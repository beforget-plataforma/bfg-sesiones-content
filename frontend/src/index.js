import template from './template';

const bfgFilterContent = {
  verMas: document.querySelector('.ver-mas-sesiones'),
  queryMaxWidth800: window.matchMedia("(max-width: 800px)"),
  postsTipo: [],
  postsCategory: [],
  empty: false,
  gotoSesion: 0,
  dataChunk: [],
  dataLength: 0,
  init: function () {
    console.log('media query', this.queryMaxWidth800.matches);
    
    const bfgNavFilter = document.querySelectorAll('.bfg-filter-button');
    bfgNavFilter.forEach((el) => {
      const input = el.querySelector('input');
      const type = input.parentElement.getAttribute('data-type')
      input.onclick = (e) => {
        let temp = [];
        if (e.target.checked) {
            if(type === 'tipo-sesion') {
                this.postsTipo.push(e.target.value);
            }else{
                this.postsCategory.push(e.target.value);
            }
        } else {
            if(type === 'tipo-sesion') {
                temp = this.postsTipo.filter((val) => {
                if (val !== e.target.value) {
                    return val;
                }
                });
                this.postsTipo = temp;
            }else{
                temp = this.postsCategory.filter((val) => {
                    if (val !== e.target.value) {
                        return val;
                    }
                });
                this.postsCategory = temp;
            } 
        }
        this.getPosts(type);
      };
    });
    bfgFilterContent.verMas.addEventListener('click', () => {
      console.log(bfgFilterContent.gotoSesion +1, bfgFilterContent.dataLength);
      
      if((bfgFilterContent.gotoSesion +1) === bfgFilterContent.dataLength){
        bfgFilterContent.verMas.disabled = true
        bfgFilterContent.verMas.classList.remove('active')
      }
      bfgFilterContent.render(bfgFilterContent.dataChunk[bfgFilterContent.gotoSesion], 'append');
      bfgFilterContent.gotoSesion ++;
    })
  },
  render: (data, type) => {
    let temp = [];
    const wrapperContent = document.querySelector('.wrapper-post-list-sesiones');
    data.forEach((el) => {
      const post = template(el);
      temp.push(post);
    });
    if(type === 'inner'){
      wrapperContent.innerHTML = temp.join('');
    }else{
      wrapperContent.insertAdjacentHTML('beforeend', temp.join(''));
    }

  },
  getPosts: (type) => {
    const filterSesionesTipo = document.querySelector('#bfg-filter-tipo-sesiones');
    const data = new FormData();

    if(bfgFilterContent.postsCategory.length === 0 && bfgFilterContent.postsTipo.length === 0 ){
      bfgFilterContent.postsTipo = ["beforget-express", "beforget-club", "beforget-plus", "beforget-talent", "beforget-basics", "beforget-talks", "beforget-proximamente"];
      bfgFilterContent.empty = true;
    }

    data.append('action', 'searchPostContent');
    data.append('nonce', wp_pageviews_ajax.nonce);
    data.append('is_user_logged_in', wp_pageviews_ajax.is_user_logged_in);
    data.append('searchTipo', bfgFilterContent.postsTipo);
    data.append('searchCategory', bfgFilterContent.postsCategory);
    data.append('type', type);

    filterSesionesTipo.classList.add('loading');

    fetch(wp_pageviews_ajax.ajax_url, {
      method: 'POST',
      credentials: 'same-origin',
      body: data,
    })
      .then((response) => {
        return response.json();
      })
      .then((sesiones) => {
        const chunk = (arr, size) => arr.reduce((acc, e, i) => (i % size ? acc[acc.length - 1].push(e) : acc.push([e]), acc), []);
        bfgFilterContent.dataChunk = chunk(sesiones, 9)
        bfgFilterContent.gotoSesion = 0;
        bfgFilterContent.dataLength = bfgFilterContent.dataChunk.length

        if(bfgFilterContent.dataChunk.length > 1 ){
          bfgFilterContent.verMas.classList.add('active')
          bfgFilterContent.verMas.disabled = false
        }
        if (sesiones) {
          bfgFilterContent.render(bfgFilterContent.dataChunk[0], 'inner');

          if(bfgFilterContent.empty) {
            bfgFilterContent.postsTipo = [];
            bfgFilterContent.postsCategory = [];
            bfgFilterContent.empty = false;
          }
        }
        filterSesionesTipo.classList.remove('loading');
      })
      .catch((error) => {
        console.log('[WP Pageviews Plugin]');
        console.error(error);
      });
  },
};

document.addEventListener('DOMContentLoaded', function () {
  bfgFilterContent.init();
  bfgFilterContent.getPosts('tipo-sesion');
});
