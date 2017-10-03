const buildTranscriptMarkup = (data, {
  highlight,
  onEach,
  onComplete,
  useDescription
}) => {
  if(!data) return
  console.log('useDescription', useDescription)

  let paragraphInit = true;
  let paragraphOpen = false;
  const html = data.reduce((acc, node) => {
    let markup = '';
    let replaced = false

    if(highlight){
      replaced = node.contents.replace(new RegExp(`(${highlight})`, 'ig'), '<span class="transcript-highlight">$1</span>');
    }

    switch(node.type){
      case 'description':
        if(useDescription){
          markup += paragraphOpen ? '</div>' : '';
          markup += `<div class="transcript-description">${replaced || node.contents}</div>`;
        }
        break;
      case 'paragraph_break':
        markup += paragraphInit ? '<div class="able-transcript-block">' : '</div>';
        markup += paragraphOpen ? '<div class="able-transcript-block">' : '';
        paragraphInit = false;
        paragraphOpen = !paragraphOpen;
        break;
      case 'section_break':
        markup += paragraphOpen ? '</div>' : '';
        markup += `<div data-highlight="transcript" class="transcript-section able-unspoken" data-timestamp="${node.start}">${replaced || node.contents}</div>`;
        break;
      case 'speaker_break':
        markup += paragraphOpen ? '</div>' : '';
        markup += `<div data-highlight="next" class="transcript-speaker able-unspoken">${replaced || node.contents}</div>`;
        break;
      case 'transcript_node':
        markup = `<span tabindex="0" class="able-transcript-seekpoint able-transcript-caption transcript-node" data-highlight="transcript" data-start="${node.start}" data-end="${node.end}">${replaced || node.contents}</span>&nbsp;`;
        console.log(markup)
        break;
    }

    if(typeof onEach === 'function') onEach(node)
    return acc + markup;
  }, '')
  return html;
}

module.exports = buildTranscriptMarkup
