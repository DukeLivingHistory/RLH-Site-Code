const buildTranscriptMarkup = (data, {
  onEach,
  onComplete,
  useDescription
}) => {
  if(!data) return

  let paragraphInit = true;
  let paragraphOpen = false;
  const html = data.reduce((acc, node) => {
    let markup = '';
    let replaced = false

    const quotes = (string) => string.replace(/"/g, '\'')

    switch(node.type){
      case 'description':
        if(useDescription){
          markup += paragraphOpen ? '</div>' : '';
          markup += `<div data-node="[Audio Description: ${node.contents}]" class="transcript-description">[Audio Description: ${node.contents}]</div>`;
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
        markup += `<div data-node="${quotes(node.contents)}" data-highlight="transcript" class="transcript-section able-unspoken" data-timestamp="${node.start}">${node.contents}</div>`;
        break;
      case 'speaker_break':
        markup += paragraphOpen ? '</div>' : '';
        markup += `<div data-node="${quotes(node.contents)}" data-highlight="next" class="transcript-speaker able-unspoken">${node.contents}</div>`;
        break;
      case 'transcript_node':
        const contents = node.contents.replace('href', 'target="_blank" data-highlight="parent" href')
        markup = `<span data-node="${quotes(node.contents)}" tabindex="0" class="able-transcript-seekpoint able-transcript-caption transcript-node" data-highlight="transcript" data-start="${node.start}" data-end="${node.end}">${contents}</span>&nbsp;`;
        break;
    }

    if(typeof onEach === 'function') onEach(node)
    return acc + markup;
  }, '')
  return html;
}

module.exports = buildTranscriptMarkup
