import { getServicesTerms } from './services';
import template from './template';

let transformObjToArray = [];
let transformObjToArrayCatSesiones = [];

const bfgFilterContent = {
  verMas: document.querySelector('.ver-mas-sesiones'),
  bfgNavFilter: document.querySelectorAll('.bfg-filter-button'),
  filterSesionesTipo: document.querySelector('#bfg-filter-tipo-sesiones'),
  resultadosSesionesTipo: document.querySelector('.bfg-count-resultados'),
  displayCatAndType: [],
  postsTipo: [],
  postsCategory: [],
  empty: false,
  gotoSesion: 0,
  dataChunk: [],
  dataLength: 0,
  initialTaxSesiones: [],
  initApp: false,
  init: function() {
    bfgFilterContent.bfgNavFilter.forEach((el) => {
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
        bfgFilterContent.initApp = true;
      };
    });
    bfgFilterContent.verMas.addEventListener('click', () => {
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
  stateFilter: (state, opacity, cursor) => {
    bfgFilterContent.bfgNavFilter.forEach(el => {
      const _check = el.querySelector('input');
      _check.parentElement.style.opacity = opacity;
      _check.parentElement.style.cursor = cursor
      _check.disabled = state;
    })
  },
  getPosts: async(type) => {
    const data = new FormData();

    if(!Array.isArray(wp_pageviews_ajax.taxSesionesType)) {
      for (const property in wp_pageviews_ajax.taxSesionesType) {
        transformObjToArray.push(wp_pageviews_ajax.taxSesionesType[property]);
      }
    }else{
      transformObjToArray = wp_pageviews_ajax.taxSesionesType;
    }
    
    if(!Array.isArray(wp_pageviews_ajax.taxSesionesCat)) {
      for (const property in wp_pageviews_ajax.taxSesionesCat) {
        transformObjToArrayCatSesiones.push(wp_pageviews_ajax.taxSesionesCat[property]);
      }
    }else{
      transformObjToArrayCatSesiones = wp_pageviews_ajax.taxSesionesCat;
    }


    if(bfgFilterContent.postsCategory.length === 0 && bfgFilterContent.postsTipo.length === 0 ){
      if(wp_pageviews_ajax.taxSesionesType) {
        bfgFilterContent.postsTipo = transformObjToArray.map(el => {
          return el.slug
        })
      }
      bfgFilterContent.postsCategory = transformObjToArrayCatSesiones.map(el => {
        return el.slug
      });
      bfgFilterContent.empty = true;
    }

    data.append('action', 'searchPostContent');
    data.append('nonce', wp_pageviews_ajax.nonce);
    data.append('is_user_logged_in', wp_pageviews_ajax.is_user_logged_in);
    data.append('searchTipo', bfgFilterContent.postsTipo);
    data.append('searchCategory', bfgFilterContent.postsCategory);
    data.append('type', type);

    bfgFilterContent.filterSesionesTipo.classList.add('loading');
    bfgFilterContent.resultadosSesionesTipo.innerHTML = '';
    bfgFilterContent.stateFilter(true, .8, 'default');

   
    const _sesiones = await getServicesTerms(data);
    const termFromStorage = (sessionStorage.getItem('sesionesTerms') !== null) ? [...JSON.parse(sessionStorage.getItem('sesionesTerms'))] : [];
    const termsSelectedFromStorage = [
        termFromStorage, 
        ...bfgFilterContent.postsCategory, 
        ...bfgFilterContent.postsTipo
    ];
    const filterSlected = [...new Set(termsSelectedFromStorage)]
    // if(bfgFilterContent.empty) {
    //   sessionStorage.setItem('sesionesTerms', JSON.stringify([]));
    // } else {
    //   sessionStorage.setItem('sesionesTerms', JSON.stringify(termsSelectedFromStorage));
    // }


    showListPost(_sesiones);
    sessionStorage.setItem('postList', JSON.stringify(_sesiones));
    // sessionStorage.setItem('sesionesTerms', JSON.stringify(mergetermsArray));
  },
};
const showListPost = (sesiones) => {
  const chunk = (arr, size) => arr.reduce((acc, e, i) => (i % size ? acc[acc.length - 1].push(e) : acc.push([e]), acc), []);
  bfgFilterContent.dataChunk = chunk(sesiones, 9);
  bfgFilterContent.gotoSesion = 0;
  bfgFilterContent.dataLength = bfgFilterContent.dataChunk.length;

  if (bfgFilterContent.dataChunk.length >= 2) {
    bfgFilterContent.verMas.classList.add('active');
    bfgFilterContent.verMas.disabled = false;
  } else {
    bfgFilterContent.verMas.classList.remove('active');
    bfgFilterContent.verMas.disabled = true;
  }
  if (sesiones) {
    bfgFilterContent.render(bfgFilterContent.dataChunk[0], 'inner');
    bfgFilterContent.gotoSesion = 1;

    if (bfgFilterContent.empty) {
      bfgFilterContent.postsTipo = [];
      bfgFilterContent.postsCategory = [];
      bfgFilterContent.empty = false;
    }
  }
  let mergetermsArray = [];
  bfgFilterContent.filterSesionesTipo.classList.remove('loading');

  if(!bfgFilterContent.initApp) {
    if(sessionStorage.getItem('sesionesTerms') === null) {
      // sessionStorage.setItem('sesionesTerms', JSON.stringify(bfgFilterContent.displayCatAndType));
    } else {
      // bfgFilterContent.displayCatAndType = JSON.parse(sessionStorage.getItem('sesionesTerms'));
    }
  }else {
    // sessionStorage.setItem('sesionesTerms', []);
    const newArrayMerge = [...bfgFilterContent.postsCategory, ...bfgFilterContent.postsTipo]
    mergetermsArray = [...new Set(newArrayMerge)];
    // sessionStorage.setItem('sesionesTerms', JSON.stringify(mergetermsArray));
    bfgFilterContent.displayCatAndType = mergetermsArray;
    
  }

  const initialTaxSesiones = (tax) => {
    return tax.map(taxo => {
      return {
        name: taxo.name,
        slug: taxo.slug
      };
    });
  };

  const tipoSesiones = initialTaxSesiones(transformObjToArray);
  const catSesiones = initialTaxSesiones(transformObjToArrayCatSesiones);

  const getNameTypeSesiones = bfgFilterContent.displayCatAndType.map(tax => {
    const valueName = tipoSesiones.find(taxonomy => {
      if (taxonomy.slug === tax) {
        return taxonomy;
      }
    });
    return valueName;
  });
  const getNameCatSesiones = bfgFilterContent.displayCatAndType.map(tax => {
    const valueName = catSesiones.find(taxonomy => {
      if (taxonomy.slug === tax) {
        return taxonomy;
      }
    });
    return valueName;
  });
  const getNamesTaxonomies = (typeTax) => {
    const filtered = typeTax.filter(function (el) {
      return el != undefined;
    });
    return filtered;
  };
  const renderDisplayCatAndType = () => {
    const sesiones = [...getNamesTaxonomies(getNameTypeSesiones), ...getNamesTaxonomies(getNameCatSesiones)];
    const sesionesTem = sesiones.map(s => s.name);
    return sesionesTem.map(tax => `<span><b> ${tax} </b></span>`);
  };
  const temp = renderDisplayCatAndType(bfgFilterContent.displayCatAndType);
  if (bfgFilterContent.initApp && (bfgFilterContent.displayCatAndType.length > 0)) {
    bfgFilterContent.resultadosSesionesTipo.innerHTML = `<span>Hemos encontrado <b>${sesiones.length}</b> sesiones para ${temp.join(',')}.</span>`;
  } else {
    bfgFilterContent.resultadosSesionesTipo.innerHTML = `<span>Tenemos un total de <b>${sesiones.length}</b> sesiones.</span>`;
  };
  bfgFilterContent.stateFilter(false, 1, 'pointer');
}

document.addEventListener('DOMContentLoaded', function () {
  bfgFilterContent.init();
  if(sessionStorage.getItem('postList') !== null && sessionStorage.getItem('sesionesTerms') !== null) {
    showListPost(JSON.parse(sessionStorage.getItem('postList')));
    bfgFilterContent.displayCatAndType.forEach(el => {
      const activeButtonStorage = document.querySelector('.bfg-wrapper-filter').querySelector('.' + el);
      const _check = activeButtonStorage.querySelector('input');
      _check.parentElement.style.opacity = 0.8;
      _check.parentElement.style.cursor = 'default'
      _check.checked = true;
      // console.log(el.className == el);
      // console.log(bfgFilterContent.bfgNavFilter.querySelectorAll('.' + el));
    })
    return
  }
  bfgFilterContent.getPosts('tipo-sesion');
});
