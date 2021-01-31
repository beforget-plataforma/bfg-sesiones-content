const template = ({
  nombre,
  bgColor,
  smileIcon,
  tipoSesion,
  categoriaSesion,
  avatar,
  authorDefault,
  author,
  authorName,
  authorLastName,
  link,
  content,
  excerpt,
  dateIcon,
  date,
}) => {
  return `<div class="bfg-item-sesiones bfg-${tipoSesion} bfg-${categoriaSesion}">
            <a class="no-color" href="${link}">
              <div class="bfg-header-cover-sesiones item-profile flex"
                style="background-color:${bgColor}; ?>">
                <span class="bfg-icon-smile inprofile">
                  <img src="${smileIcon}" alt="">
                </span>
                <hgroup>
                  <div class="bfg-container-title item-profile ">
                    <h1>
                      ${nombre}
                    </h1>
                  </div>
                  <div class="bfg-profile-author bfg-icon-small flex">
                    ${avatar}
                    <span>
                      ${(author !== null) ? `${authorName} ${authorLastName}` : authorDefault}
                    </span>
                    <!-- <a href="<?php the_permalink(); ?>">Link</a> -->

                  </div>
                </hgroup>
              </div>
              <span class="line-divisor ${tipoSesion}"></span>
              <div class="bfg-content-inprofile">
                <p>
                  ${excerpt === '' ? content : excerpt}
                </p>
              </div>
              <div class="line footer-date"></div>
              <div class="flex bfg-footer-item">
                <div class="bfg-icon-date inprofile">
                  <img src="${dateIcon}" alt="">
                </div>
                <div class="bfg-block time-footer">
                  <p>
                      ${date}
                  </p>
                </div>
              </div>
            </a>
            </div>`;
};

export default template;
