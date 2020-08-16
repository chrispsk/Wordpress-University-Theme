class Search {
  // 1. describe and create/initiate our object
  constructor() {
    this.addSearchHTML(); //must be at the beggining
    this.resultsDiv = $("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term");
    this.isOverlayOpen = false;
    this.typingTimer;
    this.previousValue;
    this.isSpinnerVisible = false;
    this.events();
  }

  // 2. events
  events() {
    this.openButton.on("click", this.openOverlay.bind(this));

    this.closeButton.on("click", this.closeOverlay.bind(this));
    $(document).on("keydown", this.keyPressDispatcher.bind(this));
    this.searchField.on("keyup", this.typingLogic.bind(this));
  }

  // 3. methods (function, action...)
  typingLogic() {
    if (this.searchField.val() != this.previousValue) {
        clearTimeout(this.typingTimer);
  
        if (this.searchField.val()) {
          if (!this.isSpinnerVisible) {
            this.resultsDiv.html('<div class="spinner-loader"></div>');
            this.isSpinnerVisible = true;
          }
          this.typingTimer = setTimeout(this.getResults.bind(this), 750);
        } else {
          this.resultsDiv.html("");
          this.isSpinnerVisible = false;
        }
      }
  
      this.previousValue = this.searchField.val();
}

  getResults() {
    //Varianta 3
    $.getJSON(uniData.root_url+"/wp-json/goapi/v1/search?term="+ this.searchField.val(), (results) => {
      this.resultsDiv.html(`
      <div class="row">
      <div class="one-third">
      <h2 class="search-overlay__section-title">General Information</h2>
      ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No results</p>'}
        ${results.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType=='post' ? `by ${item.authorName}` : ''} </li>`).join("")}
        ${results.generalInfo.length ? '</ul>' : ''}
      </div>
      <div class="one-third">
      <h2 class="search-overlay__section-title">Programs</h2>
      ${results.programs.length ? '<ul class="link-list min-list">' : '<p>No results</p>'}
        ${results.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
        ${results.programs.length ? '</ul>' : ''}
      <h2 class="search-overlay__section-title">Professors</h2>
        ${results.professors.length ? '<ul class="professor-cards">' : '<p>No results</p>'}
        ${results.professors.map(item => `
        <li class="professor-card__list-item">
    <a class="professor-card" href="${item.permalink}">
      <img src="${item.image}" alt="" class="professor-card__image">
      <span class="professor-card__name">${item.title}</span>
    </a></li>
        `).join("")}
        ${results.professors.length ? '</ul>' : ''}
      </div>
      <div class="one-third">
      <h2 class="search-overlay__section-title">Campuses</h2>
      ${results.campuses.length ? '<ul class="link-list min-list">' : '<p>No results</p>'}
        ${results.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join("")}
        ${results.campuses.length ? '</ul>' : ''}
      <h2 class="search-overlay__section-title">Events</h2>
      ${results.events.length ? '' : '<p>No results</p>'}
        ${results.events.map(item => `
        <div class="event-summary">
          <a class="event-summary__date t-center" href="${item.permalink}">
            <span class="event-summary__month">${item.month}</span>
            <span class="event-summary__day">${item.day}</span>  
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
            <p>${item.description} 
            <a href="${item.permalink}" class="nu gray">Learn more</a></p>
          </div>
        </div>
        `).join("")}
        
      </div>
      </div>
      `);
      this.isSpinnerVisible = false;
    });
    //when method work asyncronous
    // so I can get multiple urls in the same time
    //►VARIANTA 2
    // $.when(
    //   $.getJSON(uniData.root_url+"/wp-json/wp/v2/posts?search="+ this.searchField.val()), 
    //   $.getJSON(uniData.root_url+"/wp-json/wp/v2/pages?search="+ this.searchField.val())
    //   ).then((posts, pages) => {
    //   var combineResults = posts[0].concat(pages[0]);
    //   this.resultsDiv.html(`
    //     <h2 class="search-overlay__section-title">General Information</h2>
    //     ${combineResults.length ? '<ul class="link-list min-list">' : '<p>No results</p>'}
    //     ${combineResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type=='post' ? `by ${item.authorName}` : ''} </li>`).join("")}
    //     ${combineResults.length ? '</ul>' : ''}
    // `);
    // this.isSpinnerVisible = false;
    // }, () => {
    //   this.resultsDiv.html('<p>Unexpected Error. Please try again!</p>');
    // });
    //►VARIANTA 1

    //http://localhost/wordpress/wp-json/wp/v2/posts?search=
    //$.getJSON(uniData.root_url+"/wp-json/wp/v2/posts?search="+ this.searchField.val(),posts => {
    //make sure is the right this... so use posts => {} or .bind(this)  
    //$.getJSON(uniData.root_url+"/wp-json/wp/v2/pages?search="+ this.searchField.val(), pages => {
    //   var combineResults = posts.concat(pages);
    //   this.resultsDiv.html(`
    //     <h2 class="search-overlay__section-title">General Information</h2>
    //     ${combineResults.length ? '<ul class="link-list min-list">' : '<p>No results</p>'}
    //     ${combineResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join("")}
    //     ${combineResults.length ? '</ul>' : ''}
    // `); 
    // this.isSpinnerVisible = false;
    //});
    // I cannot use if statement template literals
    // but I can use ternary operator
    
    //});

  }

  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $("body").addClass("body-no-scroll");
    this.searchField.val('');
    setTimeout(() => this.searchField.focus(), 301); //wait before focus in order to work
    console.log("our open method just ran!");
    this.isOverlayOpen = true;
    return false; //this is like defaultprevent
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    $("body").removeClass("body-no-scroll");
    console.log("our close method just ran!");
    this.isOverlayOpen = false;
  }

  keyPressDispatcher(e) {
    //press S
    if (e.keyCode == 83 && !this.isOverlayOpen && document.activeElement.tagName != "INPUT" && document.activeElement.tagName != "TEXTAREA") {
      this.openOverlay();
    }
    //press Esc
    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  addSearchHTML() {
    $("body").append(`
    <div class="search-overlay">
    <div class="search-overlay__top">
    <div class="container">
    <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
    <input type="text" class="search-term" placeholder="What are you looking for" id="search-term" autocomplete="off">
    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
    </div>
    </div>
    <div class="container">
      <div id="search-overlay__results"></div>
    </div>
    </div>
    `);
  }
}

const asd = new Search();
