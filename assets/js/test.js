console.log('ok');

var httpRequest = new XMLHttpRequest();

httpRequest.addEventListener('load', clipsLoaded);
httpRequest.open('GET', 'https://api.twitch.tv/kraken/clips/top?limit=10&channel=nokss68');
httpRequest.setRequestHeader('Client-ID', 'wb57fz1kqexwbl5w03vrig184qh78h');
httpRequest.setRequestHeader('Accept', 'application/vnd.twitchtv.v5+json');
httpRequest.send();

function clipsLoaded() {
    var clipsDisplay = document.getElementById('clips-display'),
        clipList = JSON.parse(httpRequest.responseText);

    clipList.clips.forEach(function(clip, index, array) {
        clipItem = document.createElement('div');
        clipItem.innerHTML = clip.embed_html;
        clipsDisplay.appendChild(clipItem);
    });
}