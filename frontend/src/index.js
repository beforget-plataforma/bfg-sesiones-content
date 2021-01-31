import template from './template';
import handleChunk from './helpers';

const bfgFilterContent = {
  queryMaxWidth800: window.matchMedia("(max-width: 800px)"),
  postsTipo: [],
  postsCategory: [],
  empty: false,
  init: function () {
    console.log('media query', this.queryMaxWidth800.matches);
    const bfgNavFilter = document.querySelectorAll('.bfg-filter-button');
    const bfgWrapperContainer = document.querySelector('.bfg-wrapper-container');

    // bfgWrapperContainer.querySelector('#filter-sesiones-mobile').addEventListener('click', () => {
    //   bfgWrapperContainer.querySelector('.bfg-wrapper-filter').classList.add('active');
    // });
    // bfgWrapperContainer.querySelector('#bfg-ver-resultados').addEventListener('click', () => {
    //   bfgWrapperContainer.querySelector('.bfg-wrapper-filter').classList.remove('active');
    // });

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
  },
  render: (data) => {
    let temp = [];
    const wrapperContent = document.querySelector(
      '.wrapper-post-list-sesiones'
    );
    data.forEach((el) => {
      const post = template(el);
      temp.push(post);
    });
    wrapperContent.innerHTML = temp.join('');
    // console.log(template(data));
  },
  handleScroll: () => {

  },
  handlePagination: (data) => {// items per chunk
    const chunk = (sesiones, size) =>
      Array.from({ length: Math.ceil(sesiones.length / size) }, (v, i) =>
      sesiones.slice(i * size, i * size + size)
    ); 
    const sesionesCunk = chunk(data, 10);
  },
  getPosts: (type) => {
    const filterSesionesTipo = document.querySelector('#bfg-filter-tipo-sesiones');
    const totalResultados = document.querySelector('.bfg-total-resultados');

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
      .then((data) => {
        if (data) {
          bfgFilterContent.render(data);
          if(bfgFilterContent.empty) {
            bfgFilterContent.postsTipo = [];
            bfgFilterContent.postsCategory = [];
            bfgFilterContent.empty = false;
          }
          // totalResultados.querySelector('p').textContent = `Total de sesiones ${data.length}`
          // // bfgFilterContent.handlePagination(data);
          // handleChunk(data, 10).then(e => {
          //   console.log(e);
          // })
          // window.sesiones = data;
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
});
