const canvas = document.getElementById('canvas');
let playerState = 'idle';
const context = canvas.getContext('2d');

const CANVAS_WIDTH = canvas.width = 800;
const CANVAS_HEIGHT = canvas.height = 800;

const characterImage = new Image();
characterImage.src = 'images/boxman/boxman.png';
const spriteWidth = 760;
const spriteHeight = 510;

let gameFrame = 0;
const staggerFrames = 2;
const spriteAnimations = [];
const animationStates = [
    {
        name : 'idle',
        frames : 21,
    },
    {
        name : 'run',
        frames : 13,
    },
    {
        name : 'jump',
        frames : 11,
    },
    {
        name : 'fall',
        frames : 11,
    },
];
animationStates.forEach((state,index) => {
    let frames = {
        loc : [],
    }
    for (let i = 0; i < state.frames; i++) {
        let positionX = i * spriteWidth;
        let positionY = index * spriteHeight;
        frames.loc.push({x: positionX, y: positionY});
    }
    spriteAnimations[state.name] = frames;
});

let oldScrollValue = 0;
var scrollTimer = -1;
window.addEventListener('scroll',function() {
    newScrollValue = window.pageYOffset;
    if(oldScrollValue - newScrollValue < 0){
        canvas.style.transform = 'rotateY(0deg)';
    } else if(oldScrollValue - newScrollValue > 0){
        canvas.style.transform = 'rotateY(180deg)';
    }
    oldScrollValue = newScrollValue;

    let value = this.window.scrollY;
    canvas.style.left = value * 1 + 'px';
    canvas.style.animationDuration = '700ms';
    playerState = 'run';

    if (scrollTimer != -1)
        clearTimeout(scrollTimer);

      scrollTimer = window.setTimeout("scrollFinished()", 100);
});
function scrollFinished() {
    playerState = 'idle';
    canvas.style.animationDuration = '0';
}

function animate() {
    context.clearRect(0,0,CANVAS_WIDTH,CANVAS_HEIGHT); 
    let position = Math.floor(gameFrame/staggerFrames) % spriteAnimations[playerState].loc.length;
    let frameX = spriteWidth * position;
    let frameY = spriteAnimations[playerState].loc[position].y;
    // context.drawImage(image, sx, sy, sw, sh, dx, dy, dw, dh);
    context.drawImage(characterImage, frameX, frameY, 
        spriteWidth, spriteHeight, 0, 0, spriteWidth, spriteHeight);
    // if (gameFrame % staggerFrames == 0) {
    //     if(frameX < 20) frameX++;
    //     else frameX = 0;
    // }
    
    gameFrame++;
    requestAnimationFrame(animate);
}
animate();