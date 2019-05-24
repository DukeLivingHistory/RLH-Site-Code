(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';var animatePage=function(a,b,c,d){var e=d||!1;a.css('min-height','100vh'),a.append(b),c&&!IGNOREDIR?(b.addClass('pageTrans pageTrans--'+c),setTimeout(function(){b.removeClass('pageTrans--'+c)},20),setTimeout(function(){b.removeClass('pageTrans'),e&&e()},TRANSITIONTIME)):e&&e(),a.css('min-height')};module.exports=animatePage;

},{}],2:[function(require,module,exports){
'use strict';window.featherlight=require('./thirdparty/featherlight.min'),window.featherlightGallery=require('./thirdparty/featherlight.gallery.min.js');var buildPage=require('./buildPage'),destroyPage=require('./destroyPage'),eqHeight=require('./eqHeight');$(document).ready(function(){$('html').removeClass('no-js'),window.HASPAGE=!1,window.TRANSITIONTIME=500,window.DESCRIPTION='',window.JUMPTOACTIVE=!1,window.SEARCHTERM='',window.IGNOREDIR=!1,window.IS_TOUCH_SCREEN=!1,window.addEventListener('touchstart',function a(){window.IS_TOUCH_SCREEN=!0,window.removeEventListener('touchstart',a,!1)});var a=window.history,b=$('.app-wrapper'),c=$('body').attr('data-endpoint'),d=$('body').attr('data-id'),f={prev:0,curr:0};b.empty(),buildPage(b,c,d,!1),$('body').on('click','.js-internalLink',function(c){if($(c.target).attr('data-nolink'))return c.preventDefault(),!1;if(HASPAGE=!0,!IGNOREDIR){c.preventDefault();var d=$(this).attr('href'),e=$(this).attr('data-type'),g=$(this).attr('data-id');return $('html, body').animate({scrollTop:0},TRANSITIONTIME),destroyPage(b,'left'),buildPage(b,e,g,'right'),f={prev:f.curr,curr:f.curr+1},a.pushState({endpoint:e,queriedObject:g,sequence:f},null,d),!1}}),$(window).on('popstate',function(){HASPAGE||(IGNOREDIR=!0);var a=history.state?history.state.endpoint:c,e=history.state?history.state.queriedObject:d,g=!!history.state&&history.state.sequence;IGNOREDIR||(g?(f.prev===g.curr&&(destroyPage(b,'right'),buildPage(b,a,e,'left')),f.curr===g.prev&&(destroyPage(b,'left'),buildPage(b,a,e,'right')),f=g):(destroyPage(b,'right'),buildPage(b,a,e,'left')))}),eqHeight('.js-eqHeight'),$('body').on('mousedown touchstart',()=>{setTimeout(()=>{window.SOCIAL_POPUP_SHOULD_BE_REMOVED&&($('.socialPopup').remove(),window.SOCIAL_POPUP_SHOULD_BE_REMOVED=!1)},200)})});

},{"./buildPage":13,"./destroyPage":22,"./eqHeight":23,"./thirdparty/featherlight.gallery.min.js":39,"./thirdparty/featherlight.min":40}],3:[function(require,module,exports){
'use strict';const cachebust=require('./cachebust'),buildContentNode=require('./buildContentNode'),cutoff=require('./cutoff'),icon=require('./icon'),respBg=require('./respBg'),sublink=require('./sublink'),Cookies=require('js-cookie'),qs=require('query-string'),buildArchive=function(a,{items:b,image:c,name:d,total_hits:e,results:f,error:g,message:h,isSearch:i},j,k,l){const m=k&&'condense'===Cookies.get('ARCHIVEVIEW'),n=Cookies.get('ARCHIVEORDER');let o,p,q;const r=`
    <header class="contentHeader contentHeader--archive">
      <h2>${g||decodeURI(d)}</h2>
      ${c?`<figure class="heroImage js-respBg" data-set="hero" data-id="${c}"/>`:''}
    </header>
  `,s=`
    <ul class="content-feed ${m?'content-feed--contracted':''}">
      ${b?b.map(a=>buildContentNode(a)).join(' '):h||'Sorry, no results were found.'}
    </ul>
  `;if(k){let a;if(l){`
        <select name="media-select">
          ${l.map(a=>`<option value="${a.toLowerCase().replace(' ','-')}">${a}</option>`).join(' ')}
        </section>
      `}o=`
      <div class="listView">
        <span class="listView-label">change view:</span>
        <input type="radio" name="list-view" value="explode" ${m?'':'checked'}>
        ${icon('explode','listView')}
        <input type="radio" name="list-view" value="condense" ${m?'checked':''}>
        ${icon('condense','listView')}
        <span class="listview-label">change view:</span>
        <select name="list-order">
          ${[{value:'abc_asc',label:'A-Z'},{value:'abc_desc',label:'Z-A'},{value:'date_desc',label:'Date Interviewed'},{value:'publish_desc',label:'Date Published'},{value:'date_asc',label:'Date Interviewed (reverse)'},{value:'publish_asc',label:'Date Published (reverse)'}].map(({value:a,label:b})=>`<option value="${a}" ${n===a?'selected':''}>${b}</option>`).join(' ')}
        </select>
        ${a?`<span class="listView-label">media type:</span>${a}`:''}
      </div>
    `}!i&&'collections'!==j&&b&&b.length>=window.COUNT&&(p=`<button data-offset="0" class="content-load">Load More</button>`),e&&f&&(q=`
      <div>
        <p class="content-subheading">
          Showing ${e} hits across ${f} files
          <button class="content-cutoff" data-cutoff-all data-alttext='Contract All ${icon('up')}'>Expand All ${icon('down')}</button>
        </p>
      </div>`);const t=$(`${r}${q||''}${o||''}${s}${p||''}`);a.append(t);const u=a.find('.content-feed'),v=a.find('.content-load'),w=function(a=!1){const b=t.find('select[name="list-order"]'),c=t.find('select[name="media-type"]'),d=parseInt(v.attr('data-offset'))+window.COUNT,e={order:b.val(),offset:a?d:0,count:window.COUNT,include:c?c.val():null},f='search'===j?j+'/'+$('body').attr('data-search'):j,g=`/wp-json/v1/${f}?${qs.stringify(e)}${cachebust(!0)}`;$.get(g,({items:b})=>b?void(!a&&u.empty(),u.append(`
        ${b.map(a=>buildContentNode(a)).join(' ')}
      `),a?b.length<window.COUNT?v.hide():v.attr('data-offset',d):v.attr('data-offset',0)):void v.hide())},x=function(){const a=$(this).val();Cookies.set('ARCHIVEORDER',a)};t.on('click','input[name="list-view"]',function changeView(){const a=$(this).val(),b=$('.content-feed');'condense'===a?b.addClass('content-feed--contracted'):b.removeClass('content-feed--contracted'),Cookies.set('ARCHIVEVIEW',a)}),t.on('change','select[name="list-order"]',function(){w(),x.bind(this)()}),t.on('change','input[name="media-type"]',()=>{w()}),a.on('click','.content-load',()=>{w(!0)}),c&&respBg(t.find('.respImg')),sublink(t.find('[data-sublink]')),cutoff(t.find('[data-cutoff]'),t.find('[data-cutoff-all]'))};module.exports=buildArchive;

},{"./buildContentNode":8,"./cachebust":20,"./cutoff":21,"./icon":29,"./respBg":31,"./sublink":36,"js-cookie":57,"query-string":61}],4:[function(require,module,exports){
'use strict';const cachebust=require('./cachebust'),cutoff=require('./cutoff'),buildContentNode=require('./buildContentNode'),icon=require('./icon'),respImg=require('./respImg'),sublink=require('./sublink'),qs=require('query-string'),buildCollectionFeed=(a,{id:b,content:c,description:d})=>{const e=$(`
    <div class="collection-intro">
      <div class="collection-description">${d}</div>
      <div class="collection-introBottom">
        <label class="collection-searchLabel" for="filter">Search within this collection</label>
        <span class="collection-search">
          ${icon('search','type')}
          <input name="filter" type="text" placeholder="Search">
        </span>
      </div>
    </div>
    <p class="content-subheading"></p>
    <ul class="collection">
      ${c.map(a=>buildContentNode(a)).join(' ')}
    </ul>
  `);a.append(e);const f={collection:b},g=a.find('.collection'),h=a.find('.content-subheading');a.find('input').keyup(function(){window.TIMEOUT=setTimeout(()=>{const b=3<$(this).val().length?$(this).val():'null',c=`/wp-json/v1/search/${b}/any?${qs.stringify(f)}${cachebust(!0)}`;window.SEARCHTERM=b,$.get(c,({total_hits:c,items:d,results:e})=>d?void('null'===b?h.text(''):h.html(`
            <span>Showing ${c} hits across ${e} files</span>
            <button class="content-cutoff" data-cutoff-all data-alttext='Contract All ${icon('up')}'>Expand All ${icon('down')}</button>
          `),g.html(d.map(buildContentNode).join(' ')),sublink(a.find('[data-sublink]')),cutoff(a.find('[data-cutoff]'),a.find('[data-cutoff-all]'))):(h.text('No results found'),void g.empty()))},200)}).submit(a=>{a.preventDefault})};module.exports=buildCollectionFeed;

},{"./buildContentNode":8,"./cachebust":20,"./cutoff":21,"./icon":29,"./respImg":32,"./sublink":36,"query-string":61}],5:[function(require,module,exports){
'use strict';const icon=require('./icon'),respBg=require('./respBg'),respImg=require('./respImg'),sharer=require('./sharer'),buildCollectionHeader=(a,{link:b,name:c,image:d})=>{const e=sharer(b,c,c,{fullText:!1}),f=`
    <header class="contentHeader contentHeader--collection ${d?'contentHeader--hasImg':'contentHeader--noImg'}"">
      <figure class="heroImg js-respBg" data-set="hero" data-id="${d}"/>
      <div class="contentHeader-inner contentHeader-inner--hasBottom">
        <div class="contentHeader-bottom">
          <span class="contentHeader-type contentHeader-type--collection">${icon('collection','type')}Collection</span>
          <h2 class="collection-head">${c}</h2>
        </div>
      </div>
      <div class="contentHeader-imgWrapper">
        ${respImg.markup({image:d},'feat_lg','respImg contentHeader-img',null,!0)}
        <div class="shareLinks">
          Share this collection
          ${e.render}
        </div>
      </div>
    </header>
  `;a.append(f),e.attachHandlers(),respBg(a.find('.heroImg'))};module.exports=buildCollectionHeader;

},{"./icon":29,"./respBg":31,"./respImg":32,"./sharer":34}],6:[function(require,module,exports){
'use strict';var internalLink=require('./internalLink'),buildCollectionsList=function(a){var b=a[0].link_text.startsWith('The')?'':'the ',c='<p class="contentHeader-collectionsList">Part of '+b+'<strong> ';if(c+=internalLink(a[0],a[0].link_text),2<a.length)for(var d=1,e=a.length-1;d<e;d++)c+=', '+internalLink(a[d],a[d].link_text);return 1<a.length&&(2<a.length&&(c+=', '),c+=' <span class="contentheader-collectionsAnd">and</span> ',c+=internalLink(a[a.length-1],a[a.length-1].link_text)),c+='</strong> collection',1<a.length&&(c+='s'),c+='</p>',c};module.exports=buildCollectionsList;

},{"./internalLink":30}],7:[function(require,module,exports){
'use strict';const icon=require('./icon'),internalLink=require('./internalLink'),buildConnected=(a=[])=>`
    <ul class="relatedItem-wrapper">
      ${(a||[]).map(a=>`
        <li class="relatedItem relatedItem--${'post'===a.type?'blog':a.type}">
          ${internalLink(a,`${icon('post'===a.type?'blog':a.type,'type')} ${a.name}`)}
        </li>
      `).join(' ')}
    </ul>
  `;module.exports=buildConnected;

},{"./icon":29,"./internalLink":30}],8:[function(require,module,exports){
'use strict';const icon=require('./icon'),internalLink=require('./internalLink'),respImg=require('./respImg'),shortid=require('shortid'),buildContentNode=a=>{const b=({type:a,id:b,title:c,excerpt:d,hits:e,hit_count:f,img_set:g,link:h},i)=>{const j=shortid.generate();let k,l='';return e&&0<e.length&&(k=`
        <ul class="content-hits">
          ${e.slice(0,5).map(a=>`<li
            class="content-data-sublink"
            data-sublink="${a.timestamp}"
            tabindex="0"
            >${a.text}</li>
          `).join('')}
        </ul>
        ${5<e.length?`
          <ul class="content-hits hidden" id="${j}">
            ${e.slice(5).map(a=>`<li
              class="content-data-sublink"
              data-sublink="${a.timestamp}"
              tabindex="0"
              >${a.text}</li>
            `).join('')}
          </ul>
        `:''}
      `),g&&(l=`
      <div class="content-imgWrapper">
        <img
          src="${g.sizes.md}"
          class="respImg-none"
          alt="${g.alt||''}"
          ${g.caption?` data-caption="${g.caption}"`:null}
          ${g.group?` data-group="${g.group}"`:null}
        >
      </div>
      `),`
      <article class="content content--${a} ${i||''}" data-id="${b}">
        <div class="content-inner">
          <span class="content-type">${icon(a,'type')} ${a}</span>
          <h3 class="content-head">
            ${c}
            ${f?`
              &nbsp<small>(${f} total ${1<f?'hits':'hit'})</small>
              ${5<e.length?`
                <button class="content-cutoff" data-cutoff="#${j}" data-alttext='View Less ${icon('up')}' data-nolink="true">Expand ${icon('down')}</button>
              `:''}
            `:''}
          </h3>
          <div class="content-excerpt">${k||d}</div>
          <div class="content-link">View The ${a} ${icon('right','link')}</div>
        </div>
        ${l}
      </article>
    `},c=`
    ${b(a)}
    ${(a.children||[]).map(a=>b(a,'content--child'))}
  `;return internalLink({id:a.id,type:a.type,link:a.link},c)};module.exports=buildContentNode;

},{"./icon":29,"./internalLink":30,"./respImg":32,"shortid":64}],9:[function(require,module,exports){
'use strict';const internalLink=require('./internalLink'),buildInterviewsArchive=(a,{items:b,image:c,name:d,error:e})=>{const f=`
    <header class="contentHeader contentHeader--archive">
      <h2>${e||decodeURI(d)}</h2>
      ${c?`<figure class="heroImage js-respBg" data-set="hero" data-id="${c}"/>`:''}
    </header>
`,g=({id:a,title:b,subtitle:c,excerpt:d,img_set:e,link:f,collection:g,interview_date:h})=>`
  ${e?`<div class="content-gridImg"><img src="${e.sizes.md}"/></div>`:''}
  <li class="content-gridNode">
    <div class="content-gridNode-inner">
      <div class="content-gridNode-title">${b}</div>
      ${c?`<div class="content-gridNode-subtitle">${c}</div>`:''}
      ${h?`<div class="content-gridNode-date">
        ${new Date(1e3*h).toLocaleDateString('en-US',{year:'numeric',month:'long',day:'numeric'})}</div>`:''}
    </div>
  </li>
  `,h={abc:(c,a)=>c.abc_term?c.abc_term.localeCompare(a.abc_term):-1,abc_desc:(c,a)=>c.abc_term?-1*c.abc_term.localeCompare(a.abc_term):-1,date:(c,a)=>c.interview_date-a.interview_date,date_desc:(c,a)=>-1*(c.interview_date-a.interview_date),publish:(c,a)=>c.publish_date-a.publish_date,publish_desc:(c,a)=>-1*(c.publish_date-a.publish_date)},i=a=>{const b=$(`
      <div class="listView">
        <span class="listview-label">change view:</span>
        <select name="list-order">
          ${[{value:'abc',label:'A-Z'},{value:'abc_desc',label:'Z-A'},{value:'date_desc',label:'Date Interviewed'},{value:'date',label:'Date Interviewed (reverse)'},{value:'publish_desc',label:'Date Published'},{value:'publish',label:'Date Published (reverse)'},{value:'collection',label:'Collection Name'},{value:'collection_reverse',label:'Collection Name (reverse)'}].map(({value:b,label:c})=>`<option value="${b}" ${a===b?'selected':''}>${c}</option>`).join(' ')}
        </select>
      </div>
    `);return b.on('change',`select`,function(a){a.preventDefault();const b=$(this).val();'collection'===b||'collection_reverse'===b?k(b):j(b)}),b},j=c=>{const d=`<ul class="content-grid">
      ${b.sort(h[c]).map(a=>internalLink(a,g(a))).join('')}
    </ul>`;a.empty(),a.append(f),a.append(i(c)),a.append(d)},k=c=>{const d=b.reduce((a,b)=>(console.log(b),b.collections?b.collections.forEach(c=>{const d=a[c]||[];a[c]=[...d,b]}):a.ungrouped=[...a.ungrouped,b],a),{ungrouped:[]}),e=Object.entries(d).sort(([d],[a])=>{const b='collection_reverse'===c?-1:1;return'ungrouped'===d?1:'ungrouped'===a?-1:d.localeCompare(a)*b}).map(([a,b])=>`
      <div class="content-gridCollection">
        ${'ungrouped'===a?`<h2>Standalone Interviews</h2>`:`<h2>${a}</h2>`}
        <ul class="content-grid">
          ${b.map(a=>internalLink(a,g(a))).join('')}
        </ul>
      </div>
    `).join('');a.empty(),a.append(f),a.append(i(c)),a.append(e)};j('abc')};module.exports=buildInterviewsArchive;

},{"./internalLink":30}],10:[function(require,module,exports){
'use strict';const buildCollectionsList=require('./buildCollectionsList'),buildConnected=require('./buildConnected'),icon=require('./icon'),scrollIndicator=require('./scrollIndicator'),sharer=require('./sharer'),buildInterviewsHeader=(a,{id:b,video_id:c,name:d,collections:e,introduction:f,related:g,transcript_url:h,description_url:i,link:j})=>{const k=sharer(j,d,f.replace(/(<([^>]+)>)/ig,''),{fullText:!1}),l=scrollIndicator('.transcript'),m=`
    <div class="contentHeaderOuter">
      <header class="contentHeader contentHeader--interview ${c?'contentHeader--hasImg':'contentHeader--noImg'}">
        <span class="contentHeader-type">${icon('interview','type')}Interview</span>
        <div class="contentHeader-inner">
          <h2 class="contentHeader-head">${d}</h2>
          ${e?buildCollectionsList(e):''}
          ${f?`<div class="contentHeader-introduction">${f}</div>`:''}
          ${g?`
            <h3 class="contentHeader-relatedHead">Related to</h3>
            ${buildConnected(g)}
          `:''}
          <span class="contentHeader-selectWrapper" id="selectWrap-${b}" style="display: none;">
            <select class="contentHeader-select" id="select-${b}">
              <option value="null">Contents</option>
            </select>
          </span>
        </div>
        <div class="contentHeader-imgWrapper">
          <span class="contentHeader-toggleVid" data-action="toggle" data-target=".contentHeader-imgWrapper">
            <label for="toggleVid">Video Display:</label>
            <select id="toggleVid">
              <option>Small</option>
              <option selected>Medium</option>
              <option>Large</option>
              <option>Hidden</option>
            </select>
          </span>
          <video
            data-able-player
            data-youtube-id="${c}"
            data-youtube-playsinline
            ${h?`data-transcript-src="transcript-${b}"`:''}
          >
            ${h?`<track kind="captions" src="${h}">`:''}
            ${i?`<track kind="descriptions" src="${i}">`:''}
          </video>
          <a class="able-fake-pause"></a>
          <div class="contentHeader-searchwrap">
            <input class="contentHeader-search" id="video-search" placeholder="Search transcript, annotations & descriptions">
          </div>
          <div class="shareLinks">
            Share this Interview
            ${k.render}
          </div>
        </div>
        ${l.render}
      </header>
    </div>
  `;a.append(m),k.attachHandlers(),l.attachHandlers()};module.exports=buildInterviewsHeader;

},{"./buildCollectionsList":6,"./buildConnected":7,"./icon":29,"./scrollIndicator":33,"./sharer":34}],11:[function(require,module,exports){
'use strict';const icon=require('./icon'),buildMenu=(a,b)=>{const c=b.reduce((a,b)=>0<b.menu_item_parent?a.map(a=>a.ID==b.menu_item_parent?Object.assign(a,{child_items:a.child_items?[...a.child_items,b]:[b]}):a):[...a,b],[]),d=`
  <aside class="researchMenu researchMenu--app">
    <button class="researchMenu-toggle">Expand Menu ${icon('down','link')}</button>
    <ul class="menu menu--research">
      ${c.map(a=>`
        <li>
          <a href="${a.url}">${a.title}</a>
          ${a.child_items?`
              <ul>
                ${a.child_items.map(a=>`
                  <li><a href="${a.url}">${a.title}</a></li>
                `).join('')}
              </ul>
            `:''}
        </li>
      `).join('')}
    </ul>
  </aside>
  `;a.append(d)};module.exports=buildMenu;

},{"./icon":29}],12:[function(require,module,exports){
'use strict';var cachebust=require('./cachebust'),eqHeight=require('./eqHeight'),icon=require('./icon'),internalLink=require('./internalLink'),buildOtherInCollection=function(a,b,c){$.get('/wp-json/v1/collections/'+c.id+'/?count=3&not='+b+cachebust(!0),function(b){if(b.content.length){var d=$('<div class="others" />');d.append('<h3 class="others-head">Other interviews and timelines in the <strong>'+b.name+'</strong> collection</h3>');for(var e,f=0,g=b.content.length;f<g;f++)e='<article class="others-single others-single--'+b.content[f].type+'">',e+='<span class="others-singleType">'+icon(b.content[f].type,'type')+' '+b.content[f].type+'</span>',e+='<h4 class="others-singleHead">'+b.content[f].title+'</h4>',e+='<p class="others-singleDescription">'+b.content[f].excerpt,e+=internalLink(b.content[f],'View the '+b.content[f].type+' '+icon('right','link'))+'</p>',e+='</article>',d.append(e);d.append('<div class="others-wrap"><div class="others-link">'+internalLink(c,'View The Collection'+icon('right','link'))+'</div></div>'),a.append(d),eqHeight('.others-single')}})};module.exports=buildOtherInCollection;

},{"./cachebust":20,"./eqHeight":23,"./icon":29,"./internalLink":30}],13:[function(require,module,exports){
'use strict';const cachebust=require('./cachebust'),animatePage=require('./animatePage'),buildArchive=require('./buildArchive'),buildCollectionHeader=require('./buildCollectionHeader'),buildCollectionFeed=require('./buildCollectionFeed'),buildMenu=require('./buildMenu'),buildTimeline=require('./buildTimeline'),buildTimelineHeader=require('./buildTimelineHeader'),buildInterviewsArchive=require('./buildInterviewsArchive'),buildInterviewsHeader=require('./buildInterviewsHeader'),buildOtherInCollection=require('./buildOtherInCollection'),buildTranscript=require('./buildTranscript'),buildSupp=require('./buildSupp'),eqHeight=require('./eqHeight'),getNodeFromTimestamp=require('./getNodeFromTimestamp'),highlighter=require('./highlighter'),stickyHeader=require('./stickyHeader'),syncAblePlayer=require('./syncAblePlayer'),respImg=require('./respImg'),Cookies=require('js-cookie'),highlightTranscript=require('./highlightTranscript'),highlightSuppCont=require('./highlightSuppCont'),buildPage=function(a,b,c,d){$('[data-action="jumpToActive"], .socialPopup').remove(),clearInterval(JUMPTOACTIVE);const e=$('<article class="page"/>');if($('body').attr('data-endpoint',b),$('body').attr('data-id',c),'archive'!==c){const f=`/wp-json/v1/${b}/${c}`+cachebust();$.get(f,function(f){f.name&&(document.title=f.name),DESCRIPTION=f.description,'timelines'===b?(buildTimelineHeader(e,f),buildTimeline(e,f.events,f.intro,()=>{if(window.location.hash){const a=window.location.hash;setTimeout(function(){$('body, html').scrollTop($(a).offset().top)},TRANSITIONTIME)}})):'interviews'===b?(window.INSTRUCTIONS=f.instructions,f.no_media?(buildTimelineHeader(e,f,'interview'),buildTranscript(e,f.id,(a,d)=>{if(highlighter('.able-transcript'),buildSupp(d,b,c,()=>{f.collections.length&&buildOtherInCollection(e,f.id,f.collections[0])},!!a),getNodeFromTimestamp()){const a=getNodeFromTimestamp(),b=$('.contentHeaderOuter').outerHeight()+32;setTimeout(()=>{$('body, html').scrollTop(a.offset().top-b)})}})):(buildInterviewsHeader(e,f),buildTranscript(e,f.id,(a,d)=>{highlighter('.transcript'),buildSupp(d,b,c,b=>{f.collections.length&&buildOtherInCollection(e,f.id,f.collections[0]),syncAblePlayer(a,f.id,b)},!!a),stickyHeader(e,'.contentHeaderOuter','.contentHeader-inner')}))):'interactives'===b?(window.INSTRUCTIONS=f.instructions,buildTimelineHeader(e,f,'interactive'),f.menu&&buildMenu(e,window[`menu_${f.menu}`]||[]),buildTranscript(e,f.id,(a,d)=>{if(highlighter('.transcript'),buildSupp(d,b,c,null,!!a),getNodeFromTimestamp()){const a=getNodeFromTimestamp(),b=$('.contentHeader').outerHeight()+32;setTimeout(()=>{$('body, html').scrollTop(a.offset().top-b),a.addClass('able-highlight')})}})):'collections'==b&&(buildCollectionHeader(e,f),buildCollectionFeed(e,f)),animatePage(a,e,d,()=>'timelines'===b&&1>$('.respImg').length?void buildSupp(e,b,c,()=>{f.collections.length&&buildOtherInCollection(e,f.id,f.collections[0])},!0):void(respImg.load('.respImg',()=>{'timelines'==b&&buildSupp(e,b,c,()=>{window.SEARCHTERM&&(highlightTranscript(e.find('.timeline'),'[data-node]',window.SEARCHTERM),highlightSuppCont(e.find('.suppCont'),'[data-suppcont]',window.SEARCHTERM)),f.collections.length&&buildOtherInCollection(e,f.id,f.collections[0])},!0)}),eqHeight('.others-single')))})}else if('search'===b){const b=$('body').attr('data-search').replace('+',' '),c=$('body').attr('data-type')||'any',f=window.location.search.replace('?','');window.SEARCHTERM=b,document.title='Search for '+b;const g=`/wp-json/v1/search/${b}/${c}?count=${window.COUNT}&offset=0${cachebust(!0)}&${f}`;$.get(g,function(b){'interview'===c?buildInterviewArchive(e,b):buildArchive(e,Object.assign({},b,{isSearch:!0}),g),animatePage(a,e,d,function(){respImg.load('.respImg')})})}else if('interviews'===b){const b=Cookies.get('ARCHIVEORDER')||'abc',c=`/wp-json/v1/interviews?order=${b}&count=-1&include=all`+cachebust(!0);$.get(c,function(b){buildInterviewsArchive(e,b),animatePage(a,e,d,function(){respImg.load('.respImg')})})}else{const c='/wp-json/v1/'+b+'?count='+window.COUNT+'&offset=0'+cachebust(!0);$.get(c,function(c){buildArchive(e,c,b,!1,!1),animatePage(a,e,d,function(){respImg.load('.respImg')})})}};module.exports=buildPage;

},{"./animatePage":1,"./buildArchive":3,"./buildCollectionFeed":4,"./buildCollectionHeader":5,"./buildInterviewsArchive":9,"./buildInterviewsHeader":10,"./buildMenu":11,"./buildOtherInCollection":12,"./buildSupp":14,"./buildTimeline":16,"./buildTimelineHeader":17,"./buildTranscript":18,"./cachebust":20,"./eqHeight":23,"./getNodeFromTimestamp":24,"./highlightSuppCont":26,"./highlightTranscript":27,"./highlighter":28,"./respImg":32,"./stickyHeader":35,"./syncAblePlayer":37,"js-cookie":57}],14:[function(require,module,exports){
'use strict';const cachebust=require('./cachebust'),buildSuppInner=require('./buildSuppInner'),getUrlWithNoHash=require('./getUrlWithNoHash'),icon=require('./icon'),internalLink=require('./internalLink'),sharer=require('./sharer'),syncTimestamps=require('./syncTimestamps'),buildSupp=(a,b,c,d,e)=>{$.get(`/wp-json/v1/${b}/${c}/supp${cachebust()}`,c=>{const f={},g=[];c.forEach(({type:a,data:b,open:c,timestamp:d})=>{d=d.toString(),d||0===d&&e?(f[d]=f[d]||[],f[d].push({type:a,data:b,open:c})):g.push({type:a,data:b})});let h=0;const i=[];let j='';for(const a in f)j+=`
        <ul class="suppCont-inner" data-timestamp="${a}">
          ${f[a].map(a=>{var b=buildSuppInner(a);const c=b.preview,d=b.cont,e=`${getUrlWithNoHash()}#sc-${h}`,f=sharer(e,c,c,{clipboardText:`"${c}" \n${e}`,copyText:'Annotation copied to clipboard!'});return i.push({id:f.id,options:f.options}),`
              <li tabindex="0"
                ${a.open?`data-opendefault="true"`:''}
                data-action="expand"
                data-supp="${h}"
                class="suppCont-single suppCont-single--${a.type} ${a.class?`suppCont-single--${a.class}`:''}"
              >
                <button class="suppCont-expand suppCont-expand--type" data-action="close-type">
                  ${icon(a.type,'suppExpand')}
                </button>
                <div class="suppCont-singleInner">
                  <div data-suppcont="${c}" class="suppCont-preview" aria-hidden>${c}</div>
                  <div class="suppCont-content">${d}
                    <div class="suppCont-share">
                      Share this
                      ${f.render}
                    </div>
                  </div>
                </div>
                <button data-action="close" class="suppCont-expand">
                  ${icon('expand','suppExpand')}
                </button>
              </li>
            `}).join(' ')}
        </ul>
      `,++h;const k=`<aside class="suppCont">${j}</aside>`;if(e&&a.append(k),i.forEach(({id:a,options:b})=>{sharer().attachHandlers(a,b)}),g.length){let c='content';'timelines'===b?c='timeline':'interviews'==b&&(c='interview');const d=`
        <section class="unmatched">
          <h3 class="unmatched-head">Additional content related to this ${c}</h3>
          <ul class="unmatched-list">
            ${g.map(a=>{const b=buildSuppInner(a).cont;let c=a.data.title;switch(a.type){case'text':c=a.data.content;case'blockquote':c=a.data.quote;}return`
                <li data-content='${b}' class="unmatched-item unmatched-item--${a.type}">
                  ${icon(a.type,'type')} ${c}
                </li>
              `}).join(' ')}
          </ul>
        </section>
      `;a.append(d)}'interviews'===b||'interactives'===b?syncTimestamps('.suppCont-inner','.transcript-node','.transcript'):'timelines'==b&&syncTimestamps('.suppCont-inner','.event','.timeline'),$('[data-content]').each(function(){const a=$(this).data('content');$(this).featherlight({html:`
          <div class="suppCont-lightbox">
            <div class="suppCont-content">${a}</div>
          </div>
        `,afterContent:()=>{$('body').css('overflow','hidden'),$('.featherlight-close-icon').html(icon('contract','suppContent-lightboxClose'))},afterClose:()=>{$('body').css('overflow','')}})}),d&&d({timestamps:f,unmatched:g})})};module.exports=buildSupp;

},{"./buildSuppInner":15,"./cachebust":20,"./getUrlWithNoHash":25,"./icon":29,"./internalLink":30,"./sharer":34,"./syncTimestamps":38}],15:[function(require,module,exports){
'use strict';var icon=require('./icon'),internalLink=require('./internalLink'),respImg=require('./respImg'),buildSuppInner=function(a){var b='',c='';switch(a.type){case'blockquote':b=a.data.quote,c='<blockquote data-suppcont="'+a.data.quote+'" class="suppCont-quote">'+a.data.quote+'&rdquo;',c+='<footer data-suppcont="'+a.data.attribution+'" class="suppCont-attribution">&mdash; '+a.data.attribution+'</footer>',c+='</blockquote>';break;case'externallink':b=a.data.title,c+='<span data-suppcont="'+a.data.title+'" class="suppCont-contentTitle">'+a.data.title+'</span>',a.data.description&&(c+='<p data-suppcont="'+a.data.description+'">'+a.data.description+'</p>'),c+='<a target="_blank" href="'+a.data.link_url+'">',c+=(a.data.link_text||'Visit Link')+icon('right','link'),c+='</a>',a.type='link';break;case'video':b=a.data.title,c+=`<span data-suppcont="">${a.data.title}</span>`,c+=`<div class="suppCont-contentIframe">${a.data.iframe}</div>`;break;case'file':b=a.data.title,c+='<span data-suppcont="'+a.data.title+'" class="suppCont-contentTitle">'+a.data.title+'</span>',a.data.description&&(c+='<p data-suppcont="'+a.data.description+'">'+a.data.description+'</p>'),c+='<a target="_blank" href="'+a.data.file+'">',c+='Download '+icon('right','link'),c+='</a>';break;case'gallery':b=a.data.title,c+='<span data-suppcont="'+a.data.title+'" class="suppCont-contentTitle">'+a.data.title+'</span>',a.data.description&&(c+='<p data-suppcont="'+a.data.description+'">'+a.data.description+'</p>'),c+='<div class="suppCont-gallery">';for(var d,e=0,f=a.data.imgs.length;e<f;e++)d=a.data.imgs[e],c+='<span class="suppCont-galleryImage">',c+=respImg.markup(d.img_id,'feat_xs','respImg-defer',{alt:d.alt,caption:d.caption,group:a.data.title}),c+='</span>';c+='</div>';break;case'image':b=a.data.title,c+='<span data-suppcont="'+a.data.title+'" class="suppCont-contentTitle">'+a.data.title+'</span>',c+=respImg.markup(a.data.img_id,'feat_sm','respImg-defer',{alt:a.data.alt,caption:a.data.caption}),a.data.caption&&(c+='<p data-suppcont="'+a.data.caption+'">'+a.data.caption+'</p>');break;case'internallink':b=a.data.title,a.type=a.data.type,a.class=a.data.type,c+='<span data-suppcont="'+a.data.title+'" class="suppCont-contentTitle">'+a.data.title+'</span>',c+=respImg.markup(a.data.feat_img,'feat','respImg-defer gallery-single'),c+='<p data-suppcont="'+(a.data.link_description||a.data.description)+'">'+(a.data.link_description||a.data.description)+'</p>',c+=internalLink(a.data,'View '+a.type+icon('right','link'));break;case'map_location':var g=a.data.zoom||17,h='https://maps.googleapis.com/maps/api/staticmap?center='+a.data.coords.lat+','+a.data.coords.lng+'&size=600x300&zoom='+g+'&markers=color:red%7C'+a.data.coords.lat+','+a.data.coords.lng+'&key='+MAPS_APP_ID;b=a.data.title,c+='<span data-suppcont="'+a.data.title+'" class="suppCont-contentTitle">'+a.data.title+'</span>',c+='<img src="'+h+'" alt="Map of '+a.data.title+'" />';break;case'text':var i=document.createElement('div');i.innerHTML=a.data.content,b=i.textContent||i.innerText,c='<div data-suppcont="'+a.data.content+'">'+a.data.content+'</div>';}return{preview:b,cont:c}};module.exports=buildSuppInner;

},{"./icon":29,"./internalLink":30,"./respImg":32}],16:[function(require,module,exports){
'use strict';const icon=require('./icon'),respImg=require('./respImg'),sharer=require('./sharer'),buildTimeline=(a,b,c,d)=>{const e=[],f=`
    <div class="content-intro">${c}</div>
    <ul class="timeline">
      ${b.map(({event_date:a,title:b,image:c,content:d,content_link:f,content_link_type:g,content_link_id:h,content_link_text:i},j)=>{const k=sharer(window.location.href.split('#')[0]+'#'+j,b,d.replace(/(<([^>]+)>)/ig,''),{});return e.push({id:k.id,options:k.options}),`
          <li id="${j}" class="event loaded" data-start="${a}">
            <span class="event-dot"></span>
            <date class="event-date">${a}</date>
            <h3 class="event-head" data-node="<a href='#${j}'>${b}</a>"><a href="#${j}">${b}</a></h3>
            ${c?`<div class="event-imageWrapper">
              ${respImg.markup(c,'feat_lg','respImg',null,!0)}
            </div>`:''}
            ${d.length?`<div class="event-content" data-node="${d}">${d}</div>`:''}
            ${f?`
              <a class="js-internalLink relatedItem relatedItem--${g}"
                data-type="${g}"
                data-id="${h}"
                href=${f}
              >
                ${icon(g,'type')} ${i}
              </a>
            `:''}
            <div class="event-social">
              ${k.render}
            </div>
          </li>
        `}).join(' ')}
    </ul>
  `;a.append(f),e.forEach(({id:a,options:b})=>{sharer().attachHandlers(a,b)}),d&&d(a)};module.exports=buildTimeline;

},{"./icon":29,"./respImg":32,"./sharer":34}],17:[function(require,module,exports){
'use strict';const buildCollectionsList=require('./buildCollectionsList'),buildConnected=require('./buildConnected'),icon=require('./icon'),respImg=require('./respImg'),sharer=require('./sharer'),buildTimelineHeader=(a,{id:b,link:c,name:d,image:e,introduction:f,description:g,related:h,collections:i},j=null)=>{f=f||g;const k=sharer(c,d,d,{fullText:!1}),l=`
    <header class="contentHeader contentHeader--timeline ${e?'contentHeader--hasImg':'contentHeader--noImg'}"">
      ${!1===j?'':`
        <span class="contentHeader-type">
          ${icon(j,'type')}
          ${j||'Timeline'}
        </span>
      `}
      <div class="contentHeader-inner">
        <h2 class="contentHeader-head">${d}</h2>
        ${i?buildCollectionsList(i):''}
        ${f?`<div class="contentHeader-introduction">${f}</div>`:''}
        ${h?`
          <h3 class="contentHeader-relatedHead">Related to</h3>
          ${buildConnected(h)}
        `:''}
        ${'interactive'===j?`<span class="contentHeader-selectWrapper" id="selectWrap-${b}" style="display: none;">
            <select class="contentHeader-select" id="select-${b}">
              <option value="null">Contents</option>
            </select>
          </span>`:null}
      </div>
      <div class="contentHeader-imgWrapper">
        ${e?respImg.markup(e,'feat_lg','respImg contentHeader-img',null,!0):''}
        <div class="shareLinks">
          Share
          ${k.render}
        </div>
      </div>
    </header>
  `;a.append(l),k.attachHandlers()};module.exports=buildTimelineHeader;

},{"./buildCollectionsList":6,"./buildConnected":7,"./icon":29,"./respImg":32,"./sharer":34}],18:[function(require,module,exports){
'use strict';var cachebust=require('./cachebust'),buildTranscriptMarkup=require('./buildTranscriptMarkup'),highlightTranscript=require('./highlightTranscript'),highlightSuppCont=require('./highlightSuppCont'),Cookies=require('js-cookie'),buildTranscript=function(a,b,c){var d=$('<div class="transcript-instructions-wrap">'),e=$('<section class="able-transcript-area transcript" id="transcript-'+b+'">'),f=$('<div id="transcript-inner" class="able-transcript" />'),g=c||!1;let h=a=>{if(a){const a=Cookies.get('Able-Player');if(!a)return!1;const b=JSON.parse(a);return b.preferences&&b.preferences.prefDesc}return!$('.able-button-handler-descriptions').hasClass('buttonOff')},i=()=>null;window.JUMPTOINIT=!1;const j=a=>{window.JUMPTO=$('#select-'+b),'section_break'===a.type&&(window.JUMPTO.append(`<option value="${a.start}">${a.note_chapter?'&nbsp;&nbsp;&nbsp;&nbsp;':''}${a.contents}</option>`),!JUMPTOINIT&&(window.JUMPTOINIT=!0,window.JUMPTO.parent().show(),window.JUMPTO.on('change',function(){var a=$(this).val(),b=0;if('default'===a)return void $('body,html').animate({scrollTop:0},2*TRANSITIONTIME);var b=568<=$(window).width()?$('.contentHeaderOuter').height()+16:0;$('body,html').animate({scrollTop:$('.transcript-section[data-timestamp="'+a+'"]').offset().top-b},TRANSITIONTIME),setTimeout(function(){b-=jumpto.height(),$('body,html').animate({scrollTop:$('.transcript-section[data-timestamp="'+a+'"]').offset().top-b},TRANSITIONTIME/2)},TRANSITIONTIME)})))},k='/wp-json/v1/interviews/'+b+'/transcript?return=transcript_contents'+cachebust(!0);$.get(k,function(b){if(i=()=>b,!b)return void(g&&g(b));const c=buildTranscriptMarkup(b,{onEach:j,useDescription:h(!0)});window.JUMPTO&&window.JUMPTO.append('<option value="default">Back to top</option>'),f.append(c),e.append(f),e.append('<div class="able-window-toolbar" />'),d.append('<div class="transcript-instructions">'+window.INSTRUCTIONS+'</div>'),d.append(e),a.append(d),g&&g(b,d)}),$('body').on('click','.able-button-handler-descriptions',()=>{const a=buildTranscriptMarkup(i(),{onEach:j,useDescription:h()});f.html(a)});$('body').on('keyup','#video-search',function(){window.SEARCHDEBUFF=setTimeout(()=>{const a=$(this).val(),b=!!(2<a.length)&&a;highlightTranscript(f,'[data-node]',b),highlightSuppCont('.suppCont-single','[data-suppcont]',b)},500)})};module.exports=buildTranscript;

},{"./buildTranscriptMarkup":19,"./cachebust":20,"./highlightSuppCont":26,"./highlightTranscript":27,"js-cookie":57}],19:[function(require,module,exports){
'use strict';const buildTranscriptMarkup=(a,{onEach:b,onComplete:c,useDescription:d})=>{if(!a)return;let e=!0,f=!1;const g=a.reduce((a,c)=>{let g='';const h=a=>a.replace(/"/g,'&quot;');switch(c.type){case'description':d&&(g+=f?'</div>':'',g+=`<div data-node="[Audio Description: ${c.contents}]" class="transcript-description">[Audio Description: ${c.contents}]</div>`);break;case'paragraph_break':g+=e?'<div class="able-transcript-block">':'</div>',g+=f?'<div class="able-transcript-block">':'',e=!1,f=!f;break;case'section_break':g+=f?'</div>':'',g+=`<div data-node="${h(c.contents)}" data-highlight="transcript" class="transcript-section able-unspoken" data-timestamp="${c.start}">${c.contents}</div>`;break;case'speaker_break':g+=f?'</div>':'',g+=`<div data-node="${h(c.contents)}" data-highlight="next" class="transcript-speaker able-unspoken">${c.contents}</div>`;break;case'transcript_node':const a=c.contents.replace('href','target="_blank" data-highlight="parent" href');g=`<span data-node="${h(c.contents)}" tabindex="0" class="able-transcript-seekpoint able-transcript-caption transcript-node" data-highlight="transcript" data-start="${c.start}" data-end="${c.end}">${a}</span>&nbsp;`;}return'function'==typeof b&&b(c),a+g},'');return g};module.exports=buildTranscriptMarkup;

},{}],20:[function(require,module,exports){
'use strict';var cachebust=function(a){return(a?'&':'?')+'c='+Date.now()};module.exports=cachebust;

},{}],21:[function(require,module,exports){
'use strict';const cutoff=(a,b=null)=>{const c=$(a).data('alt'),d=$(a).text(),e=function(){const a=$(this),b=$(a.data('cutoff'));a.data('orig',a.data('orig')||a.html()),a.data('on',!a.data('on')),a.data('on')?(b.show(),a.html(a.data('alttext'))):(b.hide(),a.html(a.data('orig')))};$(a).click(function(){e.bind(this)()}),b&&$(b).click(function(){const b=$(this);b.data('on',!b.data('on')),b.data('orig',b.data('orig')||b.html()),b.html(b.data('on')?b.data('alttext'):b.data('orig')),$(a).each(function(){e.bind(this)()})})};module.exports=cutoff;

},{}],22:[function(require,module,exports){
'use strict';var destroyPage=function(a,b){var c=a.find('.page:eq(0)');IGNOREDIR?c.remove():(c.css({position:'absolute',"top ":a.offset().top,left:a.offset().left,width:a.width()}),c.addClass('pageTrans pageTrans--'+b),a.css('min-height',a.height()),setTimeout(function(){c.remove()},TRANSITIONTIME))};module.exports=destroyPage;

},{}],23:[function(require,module,exports){
'use strict';var eqHeight=function(a,b){var c=0,d=b||!1,e=function(){c=0,$(a).css('height',''),$(a).each(function(){var a=$(this).height();a>c&&(c=a),d&&d()}),$(a).each(function(){$(this).css('height',c)})};e(),$(window).resize(e)};module.exports=eqHeight;

},{}],24:[function(require,module,exports){
'use strict';const getNodeFromTimestamp=()=>{if(window.location.hash){const a=window.location.hash,b=a.match(/\#(\d*)/);if(b&&b[1].length&&$('[data-start="'+b[1]+'"]').length)return $('[data-start="'+b[1]+'"]')}return!1};module.exports=getNodeFromTimestamp;

},{}],25:[function(require,module,exports){
'use strict';var getUrlWithNoHash=function(){return window.location.href.split('#')[0]};module.exports=getUrlWithNoHash;

},{}],26:[function(require,module,exports){
'use strict';const respImg=require('./respImg'),escapeRegex=require('escape-string-regexp'),highlightSuppCont=(a,b,c)=>{if(!c)return;const d=new RegExp(`(${escapeRegex(c||'')})`,'ig');$(a).each(function(){const a=$(this).find(b);let c=!1;if(a.each(function(){const a=$(this).attr('data-suppcont'),b=a.match(d);b?(c=!0,$(this).html(a.replace(d,'<span class="transcript-highlight">$1</span>'))):$(this).html(a)}),c){$(this).addClass('expand'),$(this).find('[data-action="close"] use').attr('xlink:href','#contract');const a=$(this).find('.respImg-defer');respImg.load(a)}else $(this).removeClass('expand'),$(this).find('[data-action="close"] use').attr('xlink:href','#expand')})};module.exports=highlightSuppCont;

},{"./respImg":32,"escape-string-regexp":50}],27:[function(require,module,exports){
'use strict';const escapeRegex=require('escape-string-regexp'),highlightTranscript=(a,b,c)=>{c=c&&escapeRegex(c);const d=a.find(b);$(d).each(function(){const a=$(this).attr('data-node'),b=$(this).html();if(!c)return void $(this).html(a);const d=a.replace(new RegExp(`(${c})`,'ig'),'<span class="transcript-highlight">$1</span>');return d===a?void $(this).html(a):void(d===b||$(this).html(d))})};module.exports=highlightTranscript;

},{"escape-string-regexp":50}],28:[function(require,module,exports){
'use strict';const getUrlWithNoHash=require('./getUrlWithNoHash'),sharer=require('./sharer'),highlighter=a=>{const b=(a,b,c,d,e,f)=>{const g=sharer(a,b,c,{clipboardText:`${c} \n${a}`,copyText:'Selected text plus link copied to clipboard!'}),h=$(`
      <span class="socialPopup ${e}" style="${d}">
        ${g.render}
      </span>
    `),i=$('body').find('.socialPopup');0<i.length?(i.replaceWith(h),g.attachHandlers()):($('body').append(h),g.attachHandlers()),$('.socialPopup').on('mousedown touchstart',()=>{window.SOCIAL_POPUP_SHOULD_BE_REMOVED=!1,setTimeout(()=>{window.SOCIAL_POPUP_SHOULD_BE_REMOVED=!0},100)});f||setTimeout(()=>{window.SOCIAL_POPUP_SHOULD_BE_REMOVED=!0},300)},c=a=>{const b=a.getRangeAt(0),c=b.getClientRects(),d=c[c.length-1].bottom,e=$(window).height();return d>e},d=a=>{const b=a.getRangeAt(0),c=b.getClientRects();if(c.length)return(c[0].left+c[0].right)/2},f=a=>{if(window.IS_TOUCH_SCREEN){const b=a.getRangeAt(0),c=b.getClientRects();if(c.length)return c[c.length-1].bottom-30+$(window).scrollTop()}else{const b=a.getRangeAt(0),d=b.getClientRects(),e=(d[0].top+d[0].bottom)/2;if(c(a)){const a=d[d.length-1].bottom-30;return a+$(window).scrollTop()}return e+$(window).scrollTop()}},g=(g,e)=>{let h=getUrlWithNoHash();const i=document.getSelection(),j=i.toString();if(0===j.length)return;const k=j.replace('"','&quot;'),l=i.anchorNode,m=i.focusNode,n=$(l.parentNode),o=$(m.parentNode),p=n.index()<o.index()?n:o;if(!o.parents(a).length)return;if(!n.length||!o.length)return;if(n.data('highlight')||o.data('highlight'))if('next'===p.attr('data-highlight')){const a=p.next(),b=a.attr('data-start')||a.attr('data-timestamp');h=`${h}#${b}`,window.TEMPURL=h}else if('next'===o.attr('data-highlight')){const a=o.next(),b=a.attr('data-start')||a.attr('data-timestamp');h=`${h}#${b}`,window.TEMPURL=h}else if('parent'===p.attr('data-highlight')){const a=p.parent(),b=a.attr('data-start')||a.attr('data-timestamp');h=`${h}#${b}`,window.TEMPURL=h}else if('parent'===o.attr('data-highlight')){const a=o.parent(),b=a.attr('data-start')||a.attr('data-timestamp');h=`${h}#${b}`,window.TEMPURL=h}else if('transcript'===p.attr('data-highlight')||'transcript'===o.attr('data-highlight')){const a=p.attr('data-start')||p.attr('data-timestamp')||o.attr('data-start')||o.attr('data-timestamp');h=`${h}#${a}`,window.TEMPURL=h}else h=window.TEMPURL;const q=`
      position: absolute;
      left: ${0|d(i)}px;
      top: ${0|f(i)}px;
    `;let r='';(c(i)||window.IS_TOUCH_SCREEN)&&(r='socialPopup--inverse'),window.POPUP=setTimeout(()=>{b(h,document.title,k,q,r,e)},250)};$(document).on('selectionchange',a=>g(a,!0)),$(a).on('mouseup touchend',a=>g(a,!1))};module.exports=highlighter;

},{"./getUrlWithNoHash":25,"./sharer":34}],29:[function(require,module,exports){
'use strict';var icon=function(a,b){var b=b||!1;return'link-group'===a?'<svg class="dbl icon'+(b?' icon--'+b:'')+'" viewBox="0 0 256 128"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'+a+'"></use></svg>':'condense'===a||'explode'===a?'<svg class="icon'+(b?' icon--'+b:'')+'" viewBox="0 0 72 72"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'+a+'"></use></svg>':'<svg class="icon'+(b?' icon--'+b:'')+'" viewBox="0 0 128 128"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#'+a+'"></use></svg>'};module.exports=icon;

},{}],30:[function(require,module,exports){
'use strict';const internalLink=function({type:a,id:b,link:c},d){return a+='blog'===a?'':'s',window.SEARCHTERM&&(c=`${c}?search=${window.SEARCHTERM}`),`
    <a class="${'blog'===a?'':'js-internalLink'}"
      data-type=${a}
      data-id="${b}"
      href=${c}
    >${d}</a>
  `};module.exports=internalLink;

},{}],31:[function(require,module,exports){
'use strict';const cachebust=require('./cachebust'),respBg=function(a){const b=$(a).attr('data-id'),c=$(a).attr('data-set'),d=`/wp-json/v1/images/${b}/${c}_${function getSize(){const a=$(window).width();return 1200<=a?'lg':992<=a?'md':768<=a?'sm':'xs'}()}${cachebust()}`;$.get(d,b=>{$(a).css('background-image','url('+b.requested+')')})};module.exports=respBg;

},{"./cachebust":20}],32:[function(require,module,exports){
'use strict';var cachebust=require('./cachebust'),internalLink=require('./internalLink'),respImg={markup:function markup(a,b,c,d,e,f){if(d)var g=d.alt||!1,h=d.caption||!1,i=d.group||!1;var j='';return j+='<img src="#" class="'+c+'" ',j+='data-size="'+b+'" ',j+='data-src="'+a+'" ',g&&(j+='alt="'+g+'" '),h&&(j+='data-caption="'+h+'" '),i&&(j+='data-group="'+i+'" '),e&&(j+='data-showcredit'),j+=' />',f?internalLink(f,j):j},load:function load(a,b){var c=b||!1,d=$(a),e=function(a,b){var c='';return a.author&&a.author.length?(c+='<span class="'+b+'">Photo credit: ',a.src.length&&(c+='<a href="'+a.src+'">'),c+=a.author,a.src.length&&(c+='</a>'),c+='</span>',c):''};d.each(function(b){var f=$(this).attr('data-src'),g=$(this).attr('data-size');$.get('/wp-json/v1/images/'+f+'/'+g+cachebust(),function(f){var g=new Image;g.onload=function(){if($(this).attr('src',f.requested),$(this).attr('alt',f.alt),'undefined'!=typeof $(this).attr('data-showcredit')&&$(this).after(e(f.credit,'respImg-credit')),'string'==typeof a)$(this).addClass(a.substr(1)+'--loaded');else{var g=a.selector,h=g.split(' ');g=h[h.length-1],$(this).addClass(g.substr(1)+'--loaded')}d.length-1===b&&c&&c()}.bind(this),g.src=f.requested,$(this).attr('data-img',f.original);var h={targetAttr:'data-img',afterContent:function afterContent(){$('.img-caption').remove();var a=$('.featherlight-content'),b=$('.featherlight-image').attr('src'),c=$('[data-img="'+b+'"]').attr('data-caption')||f.caption||!1;if(c){var d='';f.credit.author.length&&(d=e(f.credit,'img-credit')),a.append('<div class="img-caption">'+c+d+'</div>')}$('body').css('overflow','hidden')},afterClose:function afterClose(){$('body').css('overflow','')}};$(this).parent().hasClass('js-internalLink')||($(this).attr('data-group')?$('[data-group="'+$(this).attr('data-group')+'"]').featherlightGallery(h):$(this).featherlight(h))}.bind(this))})}};module.exports=respImg;

},{"./cachebust":20,"./internalLink":30}],33:[function(require,module,exports){
'use strict';const icon=require('./icon'),scrollIndicator=a=>{const b=`<div class="scrollIndicator">${icon('down','scrollIndicator')}</div>`,c=a=>{$(a).length&&($(window).scrollTop()+$(window).height()>$(a).offset().top+33?$('.scrollIndicator').addClass('scrollIndicator--hidden'):$('.scrollIndicator').removeClass('scrollIndicator--hidden'))},d=()=>{c(a),$(window).on('scroll resize',()=>{c(a)}),$('body').unbind('click.scrollIndicator').on('click.scrollIndicator','.scrollIndicator',()=>{const b=$(a).offset().top-33;$('body,html').animate({scrollTop:b},500)})};return{render:b,attachHandlers:()=>{d()}}};module.exports=scrollIndicator;

},{"./icon":29}],34:[function(require,module,exports){
'use strict';const Clipboard=require('clipboard'),fb=require('facebook-share-link'),shortid=require('shortid'),icon=require('./icon'),sharer=(a=window.location.href,b,c,{clipboardText:d,copyText:f='Text and link copied to clipboard!',fullText:e=!0}={})=>{const g=!!window.FB_APP_ID,h=shortid.generate(),i={url:a,title:b,quote:c,clipboardText:d||a,window:`
      height: 450,
      width: 550,
      top=${$(window).height/2-275},
      left=${$(window).width()/2-225},
      toolbar=0,
      location=0,
      menubar=0
      directories=0,
      scrollbars=0
    `},j=`
    <ul class="social social--inline" data-share-id=${h}>
      ${g?`<li data-soc="fb" tabindex="0"><span>Share on Facebook</span>${icon('facebook','social')}</li>`:''}
      <li data-soc="tw" tabindex="0"><span>Share on Twitter</span>${icon('twitter','social')}</li>
      ${e?`<li data-full-text data-clipboard-text='${i.clipboardText.replace('\'','\u2019')}' data-soc="link" tabindex="0"><span>Share on URL</span>${icon('link-group','social')}</li>`:''}
      <li data-clipboard-text="${a}" data-soc="link" tabindex="0"><span>Share on URL</span>${icon('link','social')}</li>
    </ul>
  `,k=a=>{a.on('success',a=>{const b=a.trigger.hasAttribute('data-full-text')?f:'Link copied to clipboard!';$('body').append(`
        <div class="socialCopy socialCopy--success" style="position: fixed; right: 1em; bottom: 1em;">
          ${b}
        </div>
      `),setTimeout(()=>{$('.socialCopy').remove()},2e3)})},l=(a,b)=>{const c=a||h,d=b||i,e=new Clipboard(`[data-share-id="${c}"] [data-soc="link"]`);k(e),$('body').on('click',`[data-share-id="${c}"] li`,function(a){const b=$(this).data('soc');let c='';switch(b){case'fb':if(!g)break;const e=fb(window.FB_APP_ID);return c=e({href:d.url,display:'popup',quote:d.quote}),void window.open(c,'fbShareWindow',d.window);case'tw':const f=encodeURIComponent(d.url),h=encodeURIComponent(d.quote);return c=`http://twitter.com/intent/tweet?url=${f}&text=${h}`,void window.open(c,'twShareWinow',d.window);case'link':a.preventDefault();}})};return{render:j,attachHandlers:(a,b)=>{l(a,b)},id:h,options:i}};module.exports=sharer;

},{"./icon":29,"clipboard":46,"facebook-share-link":51,"shortid":64}],35:[function(require,module,exports){
'use strict';var stickyHeader=function(a,b,c){const d=$(c).offset().top+$(c).height(),e=$(b).height();let f=!0;const g=$('video').length;let h=0;const i=function(){$(b).addClass(b.slice(1)+'--sticky'),a.css('padding-top',e),f=!1,setTimeout(function(){'undefined'!=typeof AP&&AP.refreshControls()},500)},j=function(){$(b).removeClass(b.slice(1)+'--sticky'),a.css('padding-top',''),f=!0,setTimeout(function(){'undefined'!=typeof AP&&AP.refreshControls()},500)},k=function(){if(!g)return void j();var a=$(window).scrollTop();a>d&&f?i():a<d&&!f&&j(),a<h?$(b).addClass(b.slice(1)+'--justScrolledUp'):$(b).removeClass(b.slice(1)+'--justScrolledUp'),h=a};$(window).on('scroll resize orientationchange',k),$(b).find('[data-action="top"]').click(function(){$('body, html').animate({scrollTop:0},TRANSITIONTIME)}),$(b).find('[data-action="toggle"] select').change(function(){var a=$(this).val().toLowerCase(),b=$(this).parent().attr('data-target'),c=$(b);c.removeClass(),c.addClass(b.slice(1)+' '+b.slice(1)+'--'+a),setTimeout(function(){'undefined'!=typeof AP&&AP.refreshControls()},500)}),$(b).on('click','.able-fake-pause',function(){'undefined'==typeof AP||(AP.playing?AP.pauseMedia():AP.playMedia())})};module.exports=stickyHeader;

},{}],36:[function(require,module,exports){
'use strict';const sublink=a=>{const b=function(){const a=$(this).closest('a'),b=$(this).attr('data-sublink'),c=a.data('href')||a.attr('href');a.data('href',c),a.attr('href',`${c}#${b}`)},c=function(){const a=$(this).closest('a');a.attr('href',a.data('href'))};$(a).hover(b,c).focus(b).blur(c).keypress(function pseudoClick(a){13!==a.which||$(this).closest('a').trigger('click')})};module.exports=sublink;

},{}],37:[function(require,module,exports){
'use strict';window.Cookies=require('js-cookie');const cachebust=require('./cachebust'),icon=require('./icon'),getNodeFromTimestamp=require('./getNodeFromTimestamp'),syncAblePlayer=function(a,b,c){if($('body').removeClass('hasAblePlayer'),$('video').each(function(b,d){if($('body').addClass('hasAblePlayer'),void 0!==$(d).data('able-player')){window.AP=new AblePlayer($(this),$(d));const b=a.filter(a=>'section_break'===a.type),g=b.filter(a=>!a.note_chapter).map(a=>({text:a.contents,start:a.start})),h=b.filter(a=>a.note_chapter).map(a=>({text:a.contents,start:a.start})),i=a.filter(a=>'paragraph_break'!==a.type&&'description'!==a.type).map(a=>({text:a.contents,start:a.start})),j=a.filter(a=>'description'===a.type).map(a=>({text:a.contents,start:a.start})),k=Object.entries(c.timestamps).map(a=>{const b=['content','blockquote','attribution','title','description','link_text'],c=a[1];return{text:c.reduce((a,c)=>a+b.reduce((a,b)=>(c.data[b]&&(a+=c.data[b]),a),''),''),start:parseInt(a[0])}}),l=setInterval(()=>{const a=AP.youTubePlayer;if(a&&a.getDuration&&!!a.getDuration()){const b=a.getDuration();ableplayerAddDots(AP,g,{duration:b,format:'array',color:window.HEADINGOPTS.COLOR||'#fff',width:window.HEADINGOPTS.WIDTH||1,height:window.HEADINGOPTS.HEIGHT||!1,display:window.HEADINGOPTS.DISPLAY||'line'}).then(a=>{clearInterval(l),ableplayerAddDots(a,h,{duration:b,format:'array',color:window.CHAPTEROPTS.COLOR||'#fff',width:window.CHAPTEROPTS.WIDTH||1,height:window.CHAPTEROPTS.HEIGHT||!1,display:window.CHAPTEROPTS.DISPLAY||'line'}).then(a=>{ableplayerSearch(a,'#video-search',i,{duration:b,color:window.SEARCHOPTS.COLOR||'#fff',width:window.SEARCHOPTS.WIDTH||1,height:window.SEARCHOPTS.HEIGHT||!1,display:window.SEARCHOPTS.DISPLAY||'line'}).then(a=>{ableplayerSearch(a,'#video-search',k,{duration:b,color:window.SUPP_CONT_OPTS.COLOR||'#fff',width:window.SUPP_CONT_OPTS.WIDTH||1,height:window.SUPP_CONT_OPTS.HEIGHT||!1,display:window.SUPP_CONT_OPTS.DISPLAY||'line'}).then(a=>{ableplayerSearch(a,'#video-search',j,{duration:b,color:window.AUDIOOPTS.COLOR||'#fff',width:window.AUDIOOPTS.WIDTH||1,height:window.AUDIOOPTS.HEIGHT||!1,display:window.AUDIOOPTS.DISPLAY||'line'}).then(()=>{$('#video-search').val(window.SEARCHTERM).trigger('keyup')}).catch(a=>console.log(a))}).catch(a=>console.log(a))}).catch(a=>console.log(a))}).catch(a=>console.log(a))}).catch(a=>console.log(a))}},200);var e=setInterval(function(){if('undefined'!=typeof AP.initializing){var a=$('.able-wrapper .icon-captions'),b=!1;a.on('click',function(){clearInterval(e),b=!0}),b||a.trigger('click')}},200),f=0;JUMPTOACTIVE=setInterval(function(){var a=$('.able-highlight');if(a.length){var b={top:a.offset().top,bottom:a.offset().top+a.height()},c={top:$(window).scrollTop(),bottom:$(window).scrollTop()+$(window).height()};if(b.top>c.bottom)var d=1;else if(b.bottom<c.top)var d=-1;else{var d=0;$('.transcript-jumpToActive').remove()}if(d!=f&&0!=d){$('.transcript-jumpToActive').remove();var e=$('<button data-action="jumpToActive" class="transcript-jumpToActive">'+icon(1==d?'down':'up','jump')+'Jump to active section</button>');-1==d&&e.css({top:568>=$(window).width()?0:$('.contentHeaderOuter').outerHeight(),bottom:'auto'}),$('body').append(e)}f=d}},1e3)}$('body').on('click','[data-action="jumpToActive"]',function(){$(this).hide(),$('body,html').animate({scrollTop:function(){const a=$('.contentHeaderOuter').outerHeight()+32;return $('.able-highlight').offset().top-a}()},TRANSITIONTIME)})}),getNodeFromTimestamp()){const a=getNodeFromTimestamp(),b=$('.contentHeaderOuter').outerHeight()+32;setTimeout(()=>{$('body, html').scrollTop(a.offset().top-b),a.addClass('able-highlight');const c=setInterval(function(){'undefined'==typeof AP.initializing||(a.trigger('click'),a.on('click',function(){clearInterval(c)}))},200)},300)}$('.able-wrapper').addClass('able-wrapper--loaded')};module.exports=syncAblePlayer;

},{"./cachebust":20,"./getNodeFromTimestamp":24,"./icon":29,"js-cookie":57}],38:[function(require,module,exports){
'use strict';var respImg=require('./respImg'),icon=require('./icon'),syncTimestamps=function(a,b,c){var d=function(a){if(568<$(window).width()){$('.suppCont-single').attr('style',''),a.addClass('expand'),a.find('[data-action="close"] use').attr('xlink:href','#contract');var b=a.find('.respImg-defer');respImg.load(b)}else{var c=$(a).find('.suppCont-content').html(),d=$(a).find('.icon')[0].outerHTML;$.featherlight({html:'<div class="suppCont-lightbox"><div class="suppCont-content">'+d+c+'</div></div>',afterContent:function afterContent(){$('body').css('overflow','hidden');var a=$('.featherlight-content').find('.respImg-defer');respImg.load(a),$('.featherlight-close-icon').html(icon('contract','suppContent-lightboxClose'))},afterClose:function afterClose(){$('body').css('overflow','')}})}};(function expandMultiple(a){if(!(568>=$(window).width())){console.log('expanding'),a.addClass('expand'),a.find('[data-action="close"] use').attr('xlink:href','#contract');var b=a.find('.respImg-defer');respImg.load(b),setTimeout(()=>{f()},510)}})($('[data-opendefault="true"]'));var f=function(){var d=0;$(a).each(function(e){$(this).data('match',$(this).data('match')||function(){return $(b+'[data-start="'+$(this).attr('data-timestamp')+'"]')}.bind(this)());var f=$(this).data('match');if(f.length){var g=$(f).offset().top;if(0<e){var h=g-d;h=0<h?h:0}else var i=$(c).offset().top,h=g-i;$(this).css({marginTop:h,marginBottom:'70px',transform:'translateY(-15px)',left:0,right:0}),d=$(this).offset().top+$(this).height(),$(this).on('mouseenter',function(){$(f).addClass(b.slice(1)+'--suppHover')}).on('mouseleave',function(){$(f).removeClass(b.slice(1)+'--suppHover')}),$(f).on('mouseenter',function(){$(this).addClass(a.slice(1)+'--suppHover')}.bind(this)).on('mouseleave',function(){$(this).removeClass(a.slice(1)+'--suppHover')}.bind(this));var j=!1;$(f).blur(function(){var a=$(f).next(),b=$(this).find('li:eq(0)'),c=b.find('li:last-of-type');j=!0,b.focus(),c.blur(function(){j&&a.focus(),j=!1})}.bind(this))}})};if(f(),$(window).resize(f),window.location.hash){var e=window.location.hash,g=e.match(/\#sc\-(\d*)/);if(g){var h=$('[data-supp="'+g[1]+'"]');d(h),h.parent().data('match').addClass('able-highlight');var i=$('.contentHeaderOuter').height()+16;$('body, html').scrollTop(h.offset().top-i)}}$('body').on('click','.expand [data-action="close"]',function(a){a.stopPropagation(),$(this).closest('[data-action="expand"] ').removeClass('expand'),$(this).find('use').attr('xlink:href','#expand'),$('.suppCont-single').attr('style',''),setTimeout(f,500)}),$('body').on('click','.expand [data-action="close-type"]',function(a){a.stopPropagation(),$(this).closest('[data-action="expand"] ').removeClass('expand'),$('.suppCont-single').attr('style',''),setTimeout(f,500)}),$('body').on('click','[data-action="expand"]',function(){const a=$(this);d(a),setTimeout(f,500)})};module.exports=syncTimestamps;

},{"./icon":29,"./respImg":32}],39:[function(require,module,exports){
"use strict";!function(i){"use strict";function j(a,b){if(!(this instanceof j)){var c=new j(i.extend({$source:a,$currentTarget:a.first()},b));return c.open(),c}i.featherlight.apply(this,arguments),this.chainCallbacks(f)}var a=function(b){window.console&&window.console.warn&&window.console.warn("FeatherlightGallery: "+b)};if("undefined"==typeof i)return a("Too much lightness, Featherlight needs jQuery.");if(!i.featherlight)return a("Load the featherlight plugin before the gallery plugin");var b="ontouchstart"in window||window.DocumentTouch&&document instanceof DocumentTouch,c=i.event&&i.event.special.swipeleft&&i,d=window.Hammer&&function(c){var a=new window.Hammer.Manager(c[0]);return a.add(new window.Hammer.Swipe),a},e=b&&(c||d);b&&!e&&a("No compatible swipe library detected; one must be included before featherlightGallery for swipe motions to navigate the galleries.");var f={afterClose:function afterClose(d,a){var b=this;return b.$instance.off("next."+b.namespace+" previous."+b.namespace),b._swiper&&(b._swiper.off("swipeleft",b._swipeleft).off("swiperight",b._swiperight),b._swiper=null),d(a)},beforeOpen:function beforeOpen(d,a){var f=this;return f.$instance.on("next."+f.namespace+" previous."+f.namespace,function(c){var a="next"===c.type?1:-1;f.navigateTo(f.currentNavigation()+a)}),e?f._swiper=e(f.$instance).on("swipeleft",f._swipeleft=function(){f.$instance.trigger("next")}).on("swiperight",f._swiperight=function(){f.$instance.trigger("previous")}):f.$instance.find("."+f.namespace+"-content").append(f.createNavigation("previous")).append(f.createNavigation("next")),d(a)},beforeContent:function beforeContent(e,a){var b=this.currentNavigation(),c=this.slides().length;return this.$instance.toggleClass(this.namespace+"-first-slide",0===b).toggleClass(this.namespace+"-last-slide",b===c-1),e(a)},onKeyUp:function onKeyUp(d,a){var b={37:"previous",39:"next"}[a.keyCode];return b?(this.$instance.trigger(b),!1):d(a)}};i.featherlight.extend(j,{autoBind:"[data-featherlight-gallery]"}),i.extend(j.prototype,{previousIcon:"&#9664;",nextIcon:"&#9654;",galleryFadeIn:100,galleryFadeOut:300,slides:function slides(){return this.filter?this.$source.find(this.filter):this.$source},images:function images(){return a("images is deprecated, please use slides instead"),this.slides()},currentNavigation:function currentNavigation(){return this.slides().index(this.$currentTarget)},navigateTo:function navigateTo(a){var b=this,c=b.slides(),d=c.length,e=b.$instance.find("."+b.namespace+"-inner");return a=(a%d+d)%d,b.$currentTarget=c.eq(a),b.beforeContent(),i.when(b.getContent(),e.fadeTo(b.galleryFadeOut,.2)).always(function(c){b.setContent(c),b.afterContent(),c.fadeTo(b.galleryFadeIn,1)})},createNavigation:function createNavigation(a){var b=this;return i("<span title=\""+a+"\" class=\""+this.namespace+"-"+a+"\"><span>"+this[a+"Icon"]+"</span></span>").click(function(){i(this).trigger(a+"."+b.namespace)})}}),i.featherlightGallery=j,i.fn.featherlightGallery=function(b){return j.attach(this,b)},i(document).ready(function(){j._onReady()})}(jQuery);

},{}],40:[function(require,module,exports){
"use strict";!function(k){"use strict";function i(b,a){if(!(this instanceof i)){var c=new i(b,a);return c.open(),c}this.id=i.id++,this.setup(b,a),this.chainCallbacks(i._callbackChain)}if("undefined"==typeof k)return void("console"in window&&window.console.info("Too much lightness, Featherlight needs jQuery."));var a=[],j=function(c){return a=k.grep(a,function(b){return b!==c&&0<b.$instance.closest("body").length})},l=function(h,a){var b={},c=new RegExp("^"+a+"([A-Z])(.*)");for(var d in h){var e=d.match(c);if(e){var f=(e[1]+e[2].replace(/([A-Z])/g,"-$1")).toLowerCase();b[f]=h[d]}}return b},b={keyup:"onKeyUp",resize:"onResize"},e=function(a){k.each(i.opened().reverse(),function(){return a.isDefaultPrevented()||!1!==this[b[a.type]](a)?void 0:(a.preventDefault(),a.stopPropagation(),!1)})},f=function(a){if(a!==i._globalHandlerInstalled){i._globalHandlerInstalled=a;var c=k.map(b,function(b,a){return a+"."+i.prototype.namespace}).join(" ");k(window)[a?"on":"off"](c,e)}};i.prototype={constructor:i,namespace:"featherlight",targetAttr:"data-featherlight",variant:null,resetCss:!1,background:null,openTrigger:"click",closeTrigger:"click",filter:null,root:"body",openSpeed:250,closeSpeed:250,closeOnClick:"background",closeOnEsc:!0,closeIcon:"&#10005;",loading:"",persist:!1,otherClose:null,beforeOpen:k.noop,beforeContent:k.noop,beforeClose:k.noop,afterOpen:k.noop,afterContent:k.noop,afterClose:k.noop,onKeyUp:k.noop,onResize:k.noop,type:null,contentFilters:["jquery","image","html","ajax","iframe","text"],setup:function setup(a,b){"object"!=typeof a||!1!=a instanceof k||b||(b=a,a=void 0);var h=k.extend(this,b,{target:a}),c=h.resetCss?h.namespace+"-reset":h.namespace,d=k(h.background||["<div class=\""+c+"-loading "+c+"\">","<div class=\""+c+"-content\">","<span class=\""+c+"-close-icon "+h.namespace+"-close\">",h.closeIcon,"</span>","<div class=\""+h.namespace+"-inner\">"+h.loading+"</div>","</div>","</div>"].join("")),e="."+h.namespace+"-close"+(h.otherClose?","+h.otherClose:"");return h.$instance=d.clone().addClass(h.variant),h.$instance.on(h.closeTrigger+"."+h.namespace,function(a){var b=k(a.target);("background"===h.closeOnClick&&b.is("."+h.namespace)||"anywhere"===h.closeOnClick||b.closest(e).length)&&(h.close(a),a.preventDefault())}),this},getContent:function getContent(){if(!1!==this.persist&&this.$content)return this.$content;var j=this,a=this.constructor.contentFilters,b=function(b){return j.$currentTarget&&j.$currentTarget.attr(b)},c=b(j.targetAttr),d=j.target||c||"",e=a[j.type];if(!e&&d in a&&(e=a[d],d=j.target&&c),d=d||b("href")||"",!e)for(var f in a)j[f]&&(e=a[f],d=j[f]);if(!e){var g=d;if(d=null,k.each(j.contentFilters,function(){return e=a[this],e.test&&(d=e.test(g)),!d&&e.regex&&g.match&&g.match(e.regex)&&(d=g),!d}),!d)return"console"in window&&window.console.error("Featherlight: no content filter found "+(g?" for \""+g+"\"":" (no target specified)")),!1}return e.process.call(j,d)},setContent:function setContent(a){var b=this;return(a.is("iframe")||0<k("iframe",a).length)&&b.$instance.addClass(b.namespace+"-iframe"),b.$instance.removeClass(b.namespace+"-loading"),b.$instance.find("."+b.namespace+"-inner").not(a).slice(1).remove().end().replaceWith(k.contains(b.$instance[0],a[0])?"":a),b.$content=a.addClass(b.namespace+"-inner"),b},open:function open(c){var b=this;if(b.$instance.hide().appendTo(b.root),!(c&&c.isDefaultPrevented()||!1===b.beforeOpen(c))){c&&c.preventDefault();var d=b.getContent();if(d)return a.push(b),f(!0),b.$instance.fadeIn(b.openSpeed),b.beforeContent(c),k.when(d).always(function(d){b.setContent(d),b.afterContent(c)}).then(b.$instance.promise()).done(function(){b.afterOpen(c)})}return b.$instance.detach(),k.Deferred().reject().promise()},close:function close(a){var b=this,c=k.Deferred();return!1===b.beforeClose(a)?c.reject():(0===j(b).length&&f(!1),b.$instance.fadeOut(b.closeSpeed,function(){b.$instance.detach(),b.afterClose(a),c.resolve()})),c.promise()},resize:function resize(d,a){if(d&&a){this.$content.css("width","").css("height","");var b=Math.max(d/(parseInt(this.$content.parent().css("width"),10)-1),a/(parseInt(this.$content.parent().css("height"),10)-1));1<b&&(b=a/Math.floor(a/b),this.$content.css("width",""+d/b+"px").css("height",""+a/b+"px"))}},chainCallbacks:function chainCallbacks(a){for(var b in a)this[b]=k.proxy(a[b],this,k.proxy(this[b],this))}},k.extend(i,{id:0,autoBind:"[data-featherlight]",defaults:i.prototype,contentFilters:{jquery:{regex:/^[#.]\w/,test:function test(a){return a instanceof k&&a},process:function process(a){return!1===this.persist?k(a).clone(!0):k(a)}},image:{regex:/\.(png|jpg|jpeg|gif|tiff|bmp|svg)(\?\S*)?$/i,process:function process(a){var b=this,c=k.Deferred(),d=new Image,e=k("<img src=\""+a+"\" alt=\"\" class=\""+b.namespace+"-image\" />");return d.onload=function(){e.naturalWidth=d.width,e.naturalHeight=d.height,c.resolve(e)},d.onerror=function(){c.reject(e)},d.src=a,c.promise()}},html:{regex:/^\s*<[\w!][^<]*>/,process:function process(a){return k(a)}},ajax:{regex:/./,process:function process(a){var e=k.Deferred(),c=k("<div></div>").load(a,function(d,a){"error"!==a&&e.resolve(c.contents()),e.fail()});return e.promise()}},iframe:{process:function process(a){var b=new k.Deferred,c=k("<iframe/>").hide().attr("src",a).css(l(this,"iframe")).on("load",function(){b.resolve(c.show())}).appendTo(this.$instance.find("."+this.namespace+"-content"));return b.promise()}},text:{process:function process(a){return k("<div>",{text:a})}}},functionAttributes:["beforeOpen","afterOpen","beforeContent","afterContent","beforeClose","afterClose"],readElementConfig:function readElementConfig(a,b){var h=this,d=new RegExp("^data-"+b+"-(.*)"),e={};return a&&a.attributes&&k.each(a.attributes,function(){var a=this.name.match(d);if(a){var b=this.value,c=k.camelCase(a[1]);if(0<=k.inArray(c,h.functionAttributes))b=new Function(b);else try{b=k.parseJSON(b)}catch(a){}e[c]=b}}),e},extend:function extend(a,b){var c=function(){this.constructor=a};return c.prototype=this.prototype,a.prototype=new c,a.__super__=this.prototype,k.extend(a,this,b),a.defaults=a.prototype,a},attach:function attach(a,b,c){var d=this;"object"!=typeof b||!1!=b instanceof k||c||(c=b,b=void 0),c=k.extend({},c);var e,f=c.namespace||d.defaults.namespace,l=k.extend({},d.defaults,d.readElementConfig(a[0],f),c);return a.on(l.openTrigger+"."+l.namespace,l.filter,function(f){var g=k.extend({$source:a,$currentTarget:k(this)},d.readElementConfig(a[0],l.namespace),d.readElementConfig(this,l.namespace),c),h=e||k(this).data("featherlight-persisted")||new d(b,g);"shared"===h.persist?e=h:!1!==h.persist&&k(this).data("featherlight-persisted",h),g.$currentTarget.blur(),h.open(f)}),a},current:function current(){var b=this.opened();return b[b.length-1]||null},opened:function opened(){var c=this;return j(),k.grep(a,function(b){return b instanceof c})},close:function close(c){var a=this.current();return a?a.close(c):void 0},_onReady:function _onReady(){var a=this;a.autoBind&&(k(a.autoBind).each(function(){a.attach(k(this))}),k(document).on("click",a.autoBind,function(b){b.isDefaultPrevented()||"featherlight"===b.namespace||(b.preventDefault(),a.attach(k(b.currentTarget)),k(b.target).trigger("click.featherlight"))}))},_callbackChain:{onKeyUp:function onKeyUp(a,b){return 27===b.keyCode?(this.closeOnEsc&&k.featherlight.close(b),!1):a(b)},onResize:function onResize(c,a){return this.resize(this.$content.naturalWidth,this.$content.naturalHeight),c(a)},afterContent:function afterContent(d,a){var b=d(a);return this.onResize(a),b}}}),k.featherlight=i,k.fn.featherlight=function(b,a){return i.attach(this,b,a)},k(document).ready(function(){i._onReady()})}(jQuery);

},{}],41:[function(require,module,exports){
'use strict';var img=function(a){var b=$(a),c=function(a,b){var c='';return a.author&&a.author.length?(c+='<span class="'+b+'">Photo credit: ',a.src.length&&(c+='<a href="'+a.src+'">'),c+=a.author,a.src.length&&(c+='</a>'),c+='</span>',c):''};b.each(function(){var a=$(this).attr('data-img');a.length&&$.get('/wp-json/v1/images/'+a+'/feat_lg',function(a){$(this).attr('data-img',a.original),'undefined'!=typeof $(this).attr('data-showcredit')&&$(this).append(c(a.credit,'respImg-credit'))}.bind(this))})};module.exports=img;

},{}],42:[function(require,module,exports){
'use strict';var img=require('./img');$(document).ready(function(){var a=function(a){$(a).toggleClass(a.slice(1)+'--expand')};$('.header-navToggle').click(function(){a('.header-navInner'),a('.header-navToggleButton');var b=$('.header-navToggleLabel');b.text('Menu'===b.text()?'Close':'Menu')}),$('body').on('click','.researchMenu-toggle',function(){a('.researchMenu')}),img('.js-img'),$('#author-select').change(function(){const a=$(this).val();a&&(window.location.href=a)}),$('#option_toggle').click(function(){$('.header-navInner-options').toggleClass('open')})});

},{"./img":41}],43:[function(require,module,exports){
"use strict";exports=module.exports=ap;function ap(a,b){return function(){var c=[].slice.call(arguments),d=a.slice();return d.push.apply(d,c),b.apply(this,d)}}exports.pa=pa;function pa(a,b){return function(){var c=[].slice.call(arguments);return c.push.apply(c,a),b.apply(this,c)}}exports.apa=apa;function apa(a,b,c){return function(){return c.apply(this,a.concat.apply(a,arguments).concat(b))}}exports.partial=partial;function partial(a){var b=[].slice.call(arguments,1);return ap(b,a)}exports.partialRight=partialRight;function partialRight(a){var b=[].slice.call(arguments,1);return pa(b,a)}exports.curry=curry;function curry(a){return partial(partial,a)}exports.curryRight=function(a){return partial(partialRight,a)};

},{}],44:[function(require,module,exports){
'use strict';module.exports=function(a,b){if(!a)throw new Error(b||'Expected true, got '+a)};

},{}],45:[function(require,module,exports){
'use strict';(function(a,b){if('function'==typeof define&&define.amd)define(['module','select'],b);else if('undefined'!=typeof exports)b(module,require('select'));else{var c={exports:{}};b(c,a.select),a.clipboardAction=c.exports}})(void 0,function(a,b){'use strict';function c(a,b){if(!(a instanceof b))throw new TypeError('Cannot call a class as a function')}var d=function(a){return a&&a.__esModule?a:{default:a}}(b),e='function'==typeof Symbol&&'symbol'==typeof Symbol.iterator?function(a){return typeof a}:function(a){return a&&'function'==typeof Symbol&&a.constructor===Symbol&&a!==Symbol.prototype?'symbol':typeof a},f=function(){function a(a,b){for(var c,d=0;d<b.length;d++)c=b[d],c.enumerable=c.enumerable||!1,c.configurable=!0,'value'in c&&(c.writable=!0),Object.defineProperty(a,c.key,c)}return function(b,c,d){return c&&a(b.prototype,c),d&&a(b,d),b}}(),g=function(){function a(b){c(this,a),this.resolveOptions(b),this.initSelection()}return f(a,[{key:'resolveOptions',value:function(){var a=0<arguments.length&&void 0!==arguments[0]?arguments[0]:{};this.action=a.action,this.container=a.container,this.emitter=a.emitter,this.target=a.target,this.text=a.text,this.trigger=a.trigger,this.selectedText=''}},{key:'initSelection',value:function(){this.text?this.selectFake():this.target&&this.selectTarget()}},{key:'selectFake',value:function(){var a=this,b='rtl'==document.documentElement.getAttribute('dir');this.removeFake(),this.fakeHandlerCallback=function(){return a.removeFake()},this.fakeHandler=this.container.addEventListener('click',this.fakeHandlerCallback)||!0,this.fakeElem=document.createElement('textarea'),this.fakeElem.style.fontSize='12pt',this.fakeElem.style.border='0',this.fakeElem.style.padding='0',this.fakeElem.style.margin='0',this.fakeElem.style.position='absolute',this.fakeElem.style[b?'right':'left']='-9999px';var c=window.pageYOffset||document.documentElement.scrollTop;this.fakeElem.style.top=c+'px',this.fakeElem.setAttribute('readonly',''),this.fakeElem.value=this.text,this.container.appendChild(this.fakeElem),this.selectedText=(0,d.default)(this.fakeElem),this.copyText()}},{key:'removeFake',value:function(){this.fakeHandler&&(this.container.removeEventListener('click',this.fakeHandlerCallback),this.fakeHandler=null,this.fakeHandlerCallback=null),this.fakeElem&&(this.container.removeChild(this.fakeElem),this.fakeElem=null)}},{key:'selectTarget',value:function(){this.selectedText=(0,d.default)(this.target),this.copyText()}},{key:'copyText',value:function(){var a;try{a=document.execCommand(this.action)}catch(b){a=!1}this.handleResult(a)}},{key:'handleResult',value:function(a){this.emitter.emit(a?'success':'error',{action:this.action,text:this.selectedText,trigger:this.trigger,clearSelection:this.clearSelection.bind(this)})}},{key:'clearSelection',value:function(){this.trigger&&this.trigger.focus(),window.getSelection().removeAllRanges()}},{key:'destroy',value:function(){this.removeFake()}},{key:'action',set:function(){var a=0<arguments.length&&void 0!==arguments[0]?arguments[0]:'copy';if(this._action=a,'copy'!==this._action&&'cut'!==this._action)throw new Error('Invalid "action" value, use either "copy" or "cut"')},get:function(){return this._action}},{key:'target',set:function(a){if(void 0!==a)if(a&&'object'===('undefined'==typeof a?'undefined':e(a))&&1===a.nodeType){if('copy'===this.action&&a.hasAttribute('disabled'))throw new Error('Invalid "target" attribute. Please use "readonly" instead of "disabled" attribute');if('cut'===this.action&&(a.hasAttribute('readonly')||a.hasAttribute('disabled')))throw new Error('Invalid "target" attribute. You can\'t cut text from elements with "readonly" or "disabled" attributes');this._target=a}else throw new Error('Invalid "target" value, use a valid Element')},get:function(){return this._target}}]),a}();a.exports=g});

},{"select":63}],46:[function(require,module,exports){
'use strict';(function(a,b){if('function'==typeof define&&define.amd)define(['module','./clipboard-action','tiny-emitter','good-listener'],b);else if('undefined'!=typeof exports)b(module,require('./clipboard-action'),require('tiny-emitter'),require('good-listener'));else{var c={exports:{}};b(c,a.clipboardAction,a.tinyEmitter,a.goodListener),a.clipboard=c.exports}})(void 0,function(a,b,c,d){'use strict';function e(a){return a&&a.__esModule?a:{default:a}}function f(a,b){if(!(a instanceof b))throw new TypeError('Cannot call a class as a function')}function g(a,b){if(!a)throw new ReferenceError('this hasn\'t been initialised - super() hasn\'t been called');return b&&('object'==typeof b||'function'==typeof b)?b:a}function h(a,b){if('function'!=typeof b&&null!==b)throw new TypeError('Super expression must either be null or a function, not '+typeof b);a.prototype=Object.create(b&&b.prototype,{constructor:{value:a,enumerable:!1,writable:!0,configurable:!0}}),b&&(Object.setPrototypeOf?Object.setPrototypeOf(a,b):a.__proto__=b)}function i(a,b){var c='data-clipboard-'+a;return b.hasAttribute(c)?b.getAttribute(c):void 0}var j=e(b),k=e(c),l=e(d),m='function'==typeof Symbol&&'symbol'==typeof Symbol.iterator?function(a){return typeof a}:function(a){return a&&'function'==typeof Symbol&&a.constructor===Symbol&&a!==Symbol.prototype?'symbol':typeof a},n=function(){function a(a,b){for(var c,d=0;d<b.length;d++)c=b[d],c.enumerable=c.enumerable||!1,c.configurable=!0,'value'in c&&(c.writable=!0),Object.defineProperty(a,c.key,c)}return function(b,c,d){return c&&a(b.prototype,c),d&&a(b,d),b}}(),o=function(a){function b(a,c){f(this,b);var d=g(this,(b.__proto__||Object.getPrototypeOf(b)).call(this));return d.resolveOptions(c),d.listenClick(a),d}return h(b,a),n(b,[{key:'resolveOptions',value:function(){var a=0<arguments.length&&void 0!==arguments[0]?arguments[0]:{};this.action='function'==typeof a.action?a.action:this.defaultAction,this.target='function'==typeof a.target?a.target:this.defaultTarget,this.text='function'==typeof a.text?a.text:this.defaultText,this.container='object'===m(a.container)?a.container:document.body}},{key:'listenClick',value:function(a){var b=this;this.listener=(0,l.default)(a,'click',function(a){return b.onClick(a)})}},{key:'onClick',value:function(a){var b=a.delegateTarget||a.currentTarget;this.clipboardAction&&(this.clipboardAction=null),this.clipboardAction=new j.default({action:this.action(b),target:this.target(b),text:this.text(b),container:this.container,trigger:b,emitter:this})}},{key:'defaultAction',value:function(a){return i('action',a)}},{key:'defaultTarget',value:function(a){var b=i('target',a);if(b)return document.querySelector(b)}},{key:'defaultText',value:function(a){return i('text',a)}},{key:'destroy',value:function(){this.listener.destroy(),this.clipboardAction&&(this.clipboardAction.destroy(),this.clipboardAction=null)}}],[{key:'isSupported',value:function(){var a=0<arguments.length&&void 0!==arguments[0]?arguments[0]:['copy','cut'],b='string'==typeof a?[a]:a,c=!!document.queryCommandSupported;return b.forEach(function(a){c=c&&!!document.queryCommandSupported(a)}),c}}]),b}(k.default);a.exports=o});

},{"./clipboard-action":45,"good-listener":54,"tiny-emitter":76}],47:[function(require,module,exports){
'use strict';var token='%[a-f0-9]{2}',singleMatcher=/%[a-f0-9]{2}/gi,multiMatcher=/(%[a-f0-9]{2})+/gi;function decodeComponents(a,b){try{return decodeURIComponent(a.join(''))}catch(a){}if(1===a.length)return a;b=b||1;var c=a.slice(0,b),d=a.slice(b);return Array.prototype.concat.call([],decodeComponents(c),decodeComponents(d))}function decode(a){try{return decodeURIComponent(a)}catch(d){for(var b=a.match(singleMatcher),c=1;c<b.length;c++)a=decodeComponents(b,c).join(''),b=a.match(singleMatcher);return a}}function customDecodeURIComponent(a){for(var b={"%FE%FF":'\uFFFD\uFFFD',"%FF%FE":'\uFFFD\uFFFD'},c=multiMatcher.exec(a);c;){try{b[c[0]]=decodeURIComponent(c[0])}catch(a){var d=decode(c[0]);d!==c[0]&&(b[c[0]]=d)}c=multiMatcher.exec(a)}b['%C2']='\uFFFD';for(var e,f=Object.keys(b),g=0;g<f.length;g++)e=f[g],a=a.replace(new RegExp(e,'g'),b[e]);return a}module.exports=function(a){if('string'!=typeof a)throw new TypeError('Expected `encodedURI` to be of type `string`, got `'+typeof a+'`');try{return a=a.replace(/\+/g,' '),decodeURIComponent(a)}catch(b){return customDecodeURIComponent(a)}};

},{}],48:[function(require,module,exports){
'use strict';var DOCUMENT_NODE_TYPE=9;if('undefined'!=typeof Element&&!Element.prototype.matches){var proto=Element.prototype;proto.matches=proto.matchesSelector||proto.mozMatchesSelector||proto.msMatchesSelector||proto.oMatchesSelector||proto.webkitMatchesSelector}function closest(a,b){for(;a&&a.nodeType!==9;){if('function'==typeof a.matches&&a.matches(b))return a;a=a.parentNode}}module.exports=closest;

},{}],49:[function(require,module,exports){
'use strict';var closest=require('./closest');function _delegate(a,b,c,d,e){var f=listener.apply(this,arguments);return a.addEventListener(c,f,e),{destroy:function destroy(){a.removeEventListener(c,f,e)}}}function delegate(a,b,c,d,e){return'function'==typeof a.addEventListener?_delegate.apply(null,arguments):'function'==typeof c?_delegate.bind(null,document).apply(null,arguments):('string'==typeof a&&(a=document.querySelectorAll(a)),Array.prototype.map.call(a,function(a){return _delegate(a,b,c,d,e)}))}function listener(a,b,c,d){return function(c){c.delegateTarget=closest(c.target,b),c.delegateTarget&&d.call(a,c)}}module.exports=delegate;

},{"./closest":48}],50:[function(require,module,exports){
'use strict';var matchOperatorsRe=/[|\\{}()[\]^$+*?.]/g;module.exports=function(a){if('string'!=typeof a)throw new TypeError('Expected a string');return a.replace(matchOperatorsRe,'\\$&')};

},{}],51:[function(require,module,exports){
'use strict';var assert=require('assert-ok'),Integer=require('parse-int'),qs=require('query-string'),setQuery=require('url-set-query'),Snake=require('snakecase-keys'),extend=require('xtend'),partial=require('ap').partial,pipe=require('value-pipe'),base='https://www.facebook.com/dialog/share';module.exports=function(a){return assert(Integer(a),'facebook app id is required'),pipe(partial(extend,{appId:a}),Snake,qs.stringify,partial(setQuery,'https://www.facebook.com/dialog/share'))};

},{"ap":43,"assert-ok":44,"parse-int":60,"query-string":52,"snakecase-keys":74,"url-set-query":80,"value-pipe":81,"xtend":82}],52:[function(require,module,exports){
'use strict';var strictUriEncode=require('strict-uri-encode');exports.extract=function(a){return a.split('?')[1]||''},exports.parse=function(a){return'string'==typeof a?(a=a.trim().replace(/^(\?|#|&)/,''),a?a.split('&').reduce(function(a,b){var c=b.replace(/\+/g,' ').split('='),d=c.shift(),e=0<c.length?c.join('='):void 0;return d=decodeURIComponent(d),e=void 0===e?null:decodeURIComponent(e),a.hasOwnProperty(d)?Array.isArray(a[d])?a[d].push(e):a[d]=[a[d],e]:a[d]=e,a},{}):{}):{}},exports.stringify=function(a){return a?Object.keys(a).sort().map(function(b){var c=a[b];return void 0===c?'':null===c?b:Array.isArray(c)?c.slice().sort().map(function(a){return strictUriEncode(b)+'='+strictUriEncode(a)}).join('&'):strictUriEncode(b)+'='+strictUriEncode(c)}).filter(function(a){return 0<a.length}).join('&'):''};

},{"strict-uri-encode":75}],53:[function(require,module,exports){
'use strict';exports.node=function(a){return a!==void 0&&a instanceof HTMLElement&&1===a.nodeType},exports.nodeList=function(a){var b=Object.prototype.toString.call(a);return a!==void 0&&('[object NodeList]'===b||'[object HTMLCollection]'===b)&&'length'in a&&(0===a.length||exports.node(a[0]))},exports.string=function(a){return'string'==typeof a||a instanceof String},exports.fn=function(a){var b=Object.prototype.toString.call(a);return'[object Function]'===b};

},{}],54:[function(require,module,exports){
'use strict';var is=require('./is'),delegate=require('delegate');function listen(a,b,c){if(!a&&!b&&!c)throw new Error('Missing required arguments');if(!is.string(b))throw new TypeError('Second argument must be a String');if(!is.fn(c))throw new TypeError('Third argument must be a Function');if(is.node(a))return listenNode(a,b,c);if(is.nodeList(a))return listenNodeList(a,b,c);if(is.string(a))return listenSelector(a,b,c);throw new TypeError('First argument must be a String, HTMLElement, HTMLCollection, or NodeList')}function listenNode(a,b,c){return a.addEventListener(b,c),{destroy:function destroy(){a.removeEventListener(b,c)}}}function listenNodeList(a,b,c){return Array.prototype.forEach.call(a,function(a){a.addEventListener(b,c)}),{destroy:function destroy(){Array.prototype.forEach.call(a,function(a){a.removeEventListener(b,c)})}}}function listenSelector(a,b,c){return delegate(document.body,a,b,c)}module.exports=listen;

},{"./is":53,"delegate":49}],55:[function(require,module,exports){
'use strict';var numberIsNan=require('number-is-nan');module.exports=Number.isFinite||function(a){return!('number'!=typeof a||numberIsNan(a)||a===Infinity||a===-Infinity)};

},{"number-is-nan":59}],56:[function(require,module,exports){
"use strict";var isFinite=require("is-finite");module.exports=Number.isInteger||function(a){return"number"==typeof a&&isFinite(a)&&Math.floor(a)===a};

},{"is-finite":55}],57:[function(require,module,exports){
'use strict';(function(a){var b=!1;if('function'==typeof define&&define.amd&&(define(a),b=!0),'object'==typeof exports&&(module.exports=a(),b=!0),!b){var c=window.Cookies,d=window.Cookies=a();d.noConflict=function(){return window.Cookies=c,d}}})(function(){function a(){for(var a=0,b={};a<arguments.length;a++){var c=arguments[a];for(var d in c)b[d]=c[d]}return b}function b(c){function d(b,e,f){var g;if('undefined'!=typeof document){if(1<arguments.length){if(f=a({path:'/'},d.defaults,f),'number'==typeof f.expires){var h=new Date;h.setMilliseconds(h.getMilliseconds()+864e5*f.expires),f.expires=h}f.expires=f.expires?f.expires.toUTCString():'';try{g=JSON.stringify(e),/^[\{\[]/.test(g)&&(e=g)}catch(a){}e=c.write?c.write(e,b):encodeURIComponent(e+'').replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),b=encodeURIComponent(b+''),b=b.replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent),b=b.replace(/[\(\)]/g,escape);var j='';for(var k in f)f[k]&&(j+='; '+k,!0!==f[k])&&(j+='='+f[k]);return document.cookie=b+'='+e+j}b||(g={});for(var l=document.cookie?document.cookie.split('; '):[],m=/(%[0-9A-Z]{2})+/g,n=0;n<l.length;n++){var i=l[n].split('='),o=i.slice(1).join('=');this.json||'"'!==o.charAt(0)||(o=o.slice(1,-1));try{var p=i[0].replace(m,decodeURIComponent);if(o=c.read?c.read(o,p):c(o,p)||o.replace(m,decodeURIComponent),this.json)try{o=JSON.parse(o)}catch(a){}if(b===p){g=o;break}b||(g[p]=o)}catch(a){}}return g}}return d.set=d,d.get=function(a){return d.call(d,a)},d.getJSON=function(){return d.apply({json:!0},[].slice.call(arguments))},d.defaults={},d.remove=function(b,c){d(b,'',a(c,{expires:-1}))},d.withConverter=b,d}return b(function(){})});

},{}],58:[function(require,module,exports){
'use strict';module.exports=function(a,b){for(var c={},d=Object.keys(a),e=0;e<d.length;e++){var f=d[e],g=b(f,a[f],a);c[g[0]]=g[1]}return c};

},{}],59:[function(require,module,exports){
'use strict';module.exports=Number.isNaN||function(a){return a!==a};

},{}],60:[function(require,module,exports){
'use strict';var isInteger=require('is-integer');module.exports=function(a){return'number'==typeof a?isInteger(a)?a:void 0:'string'==typeof a?/^-?\d+$/.test(a)?parseInt(a,10):void 0:void 0};

},{"is-integer":56}],61:[function(require,module,exports){
'use strict';var _slicedToArray=function(){function a(a,b){var c=[],d=!0,e=!1,f=void 0;try{for(var g,h=a[Symbol.iterator]();!(d=(g=h.next()).done)&&(c.push(g.value),!(b&&c.length===b));d=!0);}catch(a){e=!0,f=a}finally{try{!d&&h['return']&&h['return']()}finally{if(e)throw f}}return c}return function(b,c){if(Array.isArray(b))return b;if(Symbol.iterator in Object(b))return a(b,c);throw new TypeError('Invalid attempt to destructure non-iterable instance')}}();const strictUriEncode=require('strict-uri-encode'),decodeComponent=require('decode-uri-component');function encoderForArrayFormat(a){switch(a.arrayFormat){case'index':return(b,c,d)=>null===c?[encode(b,a),'[',d,']'].join(''):[encode(b,a),'[',encode(d,a),']=',encode(c,a)].join('');case'bracket':return(b,c)=>null===c?encode(b,a):[encode(b,a),'[]=',encode(c,a)].join('');default:return(b,c)=>null===c?encode(b,a):[encode(b,a),'=',encode(c,a)].join('');}}function parserForArrayFormat(a){let b;switch(a.arrayFormat){case'index':return(a,c,d)=>(b=/\[(\d*)\]$/.exec(a),a=a.replace(/\[\d*\]$/,''),b?void(void 0===d[a]&&(d[a]={}),d[a][b[1]]=c):void(d[a]=c));case'bracket':return(a,c,d)=>(b=/(\[\])$/.exec(a),a=a.replace(/\[\]$/,''),b?void 0===d[a]?void(d[a]=[c]):void(d[a]=[].concat(d[a],c)):void(d[a]=c));default:return(a,b,c)=>void 0===c[a]?void(c[a]=b):void(c[a]=[].concat(c[a],b));}}function encode(a,b){return b.encode?b.strict?strictUriEncode(a):encodeURIComponent(a):a}function keysSorter(a){return Array.isArray(a)?a.sort():'object'==typeof a?keysSorter(Object.keys(a)).sort((c,a)=>+c-+a).map(b=>a[b]):a}function extract(a){const b=a.indexOf('?');return-1===b?'':a.slice(b+1)}function parse(a,b){b=Object.assign({arrayFormat:'none'},b);const c=parserForArrayFormat(b),d=Object.create(null);if('string'!=typeof a)return d;if(a=a.trim().replace(/^[?#&]/,''),!a)return d;for(const g of a.split('&')){var e=g.replace(/\+/g,' ').split('='),f=_slicedToArray(e,2);let a=f[0],b=f[1];b=void 0===b?null:decodeComponent(b),c(decodeComponent(a),b,d)}return Object.keys(d).sort().reduce((a,b)=>{const c=d[b];return a[b]=!c||'object'!=typeof c||Array.isArray(c)?c:keysSorter(c),a},Object.create(null))}exports.extract=extract,exports.parse=parse,exports.stringify=(a,b)=>{b=Object.assign({encode:!0,strict:!0,arrayFormat:'none'},b),!1===b.sort&&(b.sort=()=>{});const c=encoderForArrayFormat(b);return a?Object.keys(a).sort(b.sort).map(d=>{const e=a[d];if(e===void 0)return'';if(null===e)return encode(d,b);if(Array.isArray(e)){const a=[];for(const b of e.slice())void 0!==b&&a.push(c(d,b,a.length));return a.join('&')}return encode(d,b)+'='+encode(e,b)}).filter(a=>0<a.length).join('&'):''},exports.parseUrl=(a,b)=>({url:a.split('?')[0]||'',query:parse(extract(a),b)});

},{"decode-uri-component":47,"strict-uri-encode":62}],62:[function(require,module,exports){
'use strict';module.exports=a=>encodeURIComponent(a).replace(/[!'()*]/g,a=>`%${a.charCodeAt(0).toString(16).toUpperCase()}`);

},{}],63:[function(require,module,exports){
'use strict';function select(a){var b;if('SELECT'===a.nodeName)a.focus(),b=a.value;else if('INPUT'===a.nodeName||'TEXTAREA'===a.nodeName){var c=a.hasAttribute('readonly');c||a.setAttribute('readonly',''),a.select(),a.setSelectionRange(0,a.value.length),c||a.removeAttribute('readonly'),b=a.value}else{a.hasAttribute('contenteditable')&&a.focus();var d=window.getSelection(),e=document.createRange();e.selectNodeContents(a),d.removeAllRanges(),d.addRange(e),b=d.toString()}return b}module.exports=select;

},{}],64:[function(require,module,exports){
'use strict';module.exports=require('./lib/index');

},{"./lib/index":69}],65:[function(require,module,exports){
'use strict';var alphabet,previousSeed,shuffled,randomFromSeed=require('./random/random-from-seed'),ORIGINAL='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';function reset(){shuffled=!1}function setCharacters(a){if(!a)return void('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-'!==alphabet&&(alphabet='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-',reset()));if(a!==alphabet){if(64!==a.length)throw new Error('Custom alphabet for shortid must be 64 unique characters. You submitted '+a.length+' characters: '+a);var b=a.split('').filter(function(a,b,c){return b!==c.lastIndexOf(a)});if(b.length)throw new Error('Custom alphabet for shortid must be 64 unique characters. These characters were not unique: '+b.join(', '));alphabet=a,reset()}}function characters(a){return setCharacters(a),alphabet}function setSeed(a){randomFromSeed.seed(a),previousSeed!==a&&(reset(),previousSeed=a)}function shuffle(){alphabet||setCharacters('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-');for(var a,b=alphabet.split(''),c=[],d=randomFromSeed.nextValue();0<b.length;)d=randomFromSeed.nextValue(),a=Math.floor(d*b.length),c.push(b.splice(a,1)[0]);return c.join('')}function getShuffled(){return shuffled?shuffled:(shuffled=shuffle(),shuffled)}function lookup(a){var b=getShuffled();return b[a]}module.exports={characters:characters,seed:setSeed,lookup:lookup,shuffled:getShuffled};

},{"./random/random-from-seed":72}],66:[function(require,module,exports){
'use strict';var counter,previousSeconds,encode=require('./encode'),alphabet=require('./alphabet'),REDUCE_TIME=1459707606518,version=6;function build(a){var b='',c=Math.floor(.001*(Date.now()-1459707606518));return c===previousSeconds?counter++:(counter=0,previousSeconds=c),b+=encode(alphabet.lookup,6),b+=encode(alphabet.lookup,a),0<counter&&(b+=encode(alphabet.lookup,counter)),b+=encode(alphabet.lookup,c),b}module.exports=build;

},{"./alphabet":65,"./encode":68}],67:[function(require,module,exports){
'use strict';var alphabet=require('./alphabet');function decode(a){var b=alphabet.shuffled();return{version:15&b.indexOf(a.substr(0,1)),worker:15&b.indexOf(a.substr(1,1))}}module.exports=decode;

},{"./alphabet":65}],68:[function(require,module,exports){
'use strict';var randomByte=require('./random/random-byte');function encode(a,b){for(var c,d=0,e='';!c;)e+=a(15&b>>4*d|randomByte()),c=b<Math.pow(16,d+1),d++;return e}module.exports=encode;

},{"./random/random-byte":71}],69:[function(require,module,exports){
'use strict';var alphabet=require('./alphabet'),encode=require('./encode'),decode=require('./decode'),build=require('./build'),isValid=require('./is-valid'),clusterWorkerId=require('./util/cluster-worker-id')||0;function seed(a){return alphabet.seed(a),module.exports}function worker(a){return clusterWorkerId=a,module.exports}function characters(a){return void 0!==a&&alphabet.characters(a),alphabet.shuffled()}function generate(){return build(clusterWorkerId)}module.exports=generate,module.exports.generate=generate,module.exports.seed=seed,module.exports.worker=worker,module.exports.characters=characters,module.exports.decode=decode,module.exports.isValid=isValid;

},{"./alphabet":65,"./build":66,"./decode":67,"./encode":68,"./is-valid":70,"./util/cluster-worker-id":73}],70:[function(require,module,exports){
'use strict';var alphabet=require('./alphabet');function isShortId(a){if(!a||'string'!=typeof a||6>a.length)return!1;for(var b=alphabet.characters(),c=a.length,d=0;d<c;d++)if(-1===b.indexOf(a[d]))return!1;return!0}module.exports=isShortId;

},{"./alphabet":65}],71:[function(require,module,exports){
'use strict';var crypto='object'==typeof window&&(window.crypto||window.msCrypto);function randomByte(){if(!crypto||!crypto.getRandomValues)return 48&Math.floor(256*Math.random());var a=new Uint8Array(1);return crypto.getRandomValues(a),48&a[0]}module.exports=randomByte;

},{}],72:[function(require,module,exports){
'use strict';var seed=1;function getNextValue(){return seed=(9301*seed+49297)%233280,seed/233280}function setSeed(a){seed=a}module.exports={nextValue:getNextValue,seed:setSeed};

},{}],73:[function(require,module,exports){
'use strict';module.exports=0;

},{}],74:[function(require,module,exports){
'use strict';var map=require('map-obj'),snakeCase=require('to-snake-case');module.exports=function(a){return map(a,function(a,b){return[snakeCase(a),b]})};

},{"map-obj":58,"to-snake-case":78}],75:[function(require,module,exports){
'use strict';module.exports=function(a){return encodeURIComponent(a).replace(/[!'()*]/g,function(a){return'%'+a.charCodeAt(0).toString(16).toUpperCase()})};

},{}],76:[function(require,module,exports){
"use strict";function E(){}E.prototype={on:function on(a,b,c){var d=this.e||(this.e={});return(d[a]||(d[a]=[])).push({fn:b,ctx:c}),this},once:function once(a,b,c){function d(){e.off(a,d),b.apply(c,arguments)}var e=this;return d._=b,this.on(a,d,c)},emit:function emit(a){var b=[].slice.call(arguments,1),c=((this.e||(this.e={}))[a]||[]).slice(),d=0,e=c.length;for(d;d<e;d++)c[d].fn.apply(c[d].ctx,b);return this},off:function off(a,b){var c=this.e||(this.e={}),d=c[a],e=[];if(d&&b)for(var f=0,g=d.length;f<g;f++)d[f].fn!==b&&d[f].fn._!==b&&e.push(d[f]);return e.length?c[a]=e:delete c[a],this}},module.exports=E;

},{}],77:[function(require,module,exports){
'use strict';module.exports=toNoCase;var hasSpace=/\s/,hasCamel=/[a-z][A-Z]/,hasSeparator=/[\W_]/;function toNoCase(a){return hasSpace.test(a)?a.toLowerCase():(hasSeparator.test(a)&&(a=unseparate(a)),hasCamel.test(a)&&(a=uncamelize(a)),a.toLowerCase())}var separatorSplitter=/[\W_]+(.|$)/g;function unseparate(a){return a.replace(separatorSplitter,function(a,b){return b?' '+b:''})}var camelSplitter=/(.)([A-Z]+)/g;function uncamelize(a){return a.replace(camelSplitter,function(a,b,c){return b+' '+c.toLowerCase().split('').join(' ')})}

},{}],78:[function(require,module,exports){
'use strict';var toSpace=require('to-space-case');module.exports=toSnakeCase;function toSnakeCase(a){return toSpace(a).replace(/\s/g,'_')}

},{"to-space-case":79}],79:[function(require,module,exports){
'use strict';var clean=require('to-no-case');module.exports=toSpaceCase;function toSpaceCase(a){return clean(a).replace(/[\W_]+(.|$)/g,function(a,b){return b?' '+b:''})}

},{"to-no-case":77}],80:[function(require,module,exports){
'use strict';module.exports=urlSetQuery;function urlSetQuery(a,b){if(b){b=b.trim().replace(/^(\?|#|&)/,''),b=b?'?'+b:b;var c=a.split(/[\?\#]/),d=c[0];b&&/\:\/\/[^\/]*$/.test(d)&&(d+='/');var e=a.match(/(\#.*)$/);a=d+b,e&&(a+=e[0])}return a}

},{}],81:[function(require,module,exports){
'use strict';module.exports=valuePipe;function valuePipe(a){if(!a)throw new TypeError('At least one function is required');return Array.isArray(a)||(a=Array.prototype.slice.call(arguments)),function(b){for(var c=0;c<a.length;c++)b=a[c](b);return b}}

},{}],82:[function(require,module,exports){
"use strict";module.exports=extend;var hasOwnProperty=Object.prototype.hasOwnProperty;function extend(){for(var a,b={},c=0;c<arguments.length;c++)for(var d in a=arguments[c],a)hasOwnProperty.call(a,d)&&(b[d]=a[d]);return b}

},{}]},{},[42,2]);
