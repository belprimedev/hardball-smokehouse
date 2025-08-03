<main class="main cursor__wrapper">

  
  <h1 class="title">Cursor / mouse pointer effect</h1>
  <h2 class="subtitle subtitle--s">Try to hover around.</h2>
  
  <!--Cursor-->
  <span class="cursor"></span>
  <span class="cursor-trail"></span>
  <!--/Cursor-->
</main>


<style>

    .cursor {
  background-color: whitesmoke;
  width: 24px;
  height: 24px;
  transform-origin: center;
  top: 0;
  left: 0;
  position: absolute;
  mix-blend-mode: exclusion;
  pointer-events: none;
  will-change: transform;
  transition: transform linear .125s, opacity .125s ease-in .125s;
  border-radius: 100%;
  opacity: 0;
  z-index: 9999;
  
  &-trail {
    width: 80px;
    height: 80px;
    border-radius: 100%;
    border: 2px solid #FF674D;
    top: -2px;
    left: -2px;
    position: absolute;
    mix-blend-mode: exclusion;
    pointer-events: none;
    will-change: transform;
    transition: transform linear .125s, opacity .125s ease-in .125s;
    border-radius: 100%;
    opacity: 0;
    z-index: 9999;
  }
}

// the following CSS is for demo purposes only
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500&display=swap');
.title {
  font-family: 'IBM Plex Sans';
  font-size: 2.5rem;
  line-height: 0.99;
  text-align: center;
  font-weight: 400;
  text-transform: uppercase;
  margin-bottom: 0.0rem;
  padding-top: 0.75rem;
  padding-left: 0.75rem;
  color: #FF674D;
  background-color: whitesmoke;
  
  @media (max-height: 600px) {
    font-size: 1.5rem;
  }
}
.subtitle {
  font-family: 'IBM Plex Sans';
  font-size: 2rem;
  line-height: 0.99;
  text-align: right;
  font-weight: 400;
  color: whitesmoke;
  background-color: #FF674D;
  margin-top: 0;
  margin-left: 25%;
  padding-right: 0.75rem;
  padding-bottom: 0.75rem;
  @media (max-height: 600px) {
    font-size: 1rem;
  }
}
.main {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: whitesmoke;
  background: #090b0d;
  
  & > * {
    z-index: 1;
  }
  &:before {
    content: "";
    position: absolute;
    top: 0%;
    left: 0%;
    width: 0;
    height: 100%;
    border-left: 10px solid whitesmoke;
    pointer-events: none;
    z-index: 0;
    
  }
  &:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 10px;
    background: transparent;
    pointer-events: none;
    z-index: 0;
    border-bottom: 10px solid whitesmoke;
    transform: translateY(-50%);
    @media (max-width: 500px) {
      content: "This pen isn't for mobile ⛔️";
      font-family: 'IBM Plex Sans';
      line-height: 1.15;
      background: black;
      color: white;
      font-size: 2rem;
      text-align: center;
      padding: 2rem;
      width: 100vw;
      height: 100vh;
      transform: none;
      word-break: break-all;
      top: 0;
      left: 0;
      right: initial;
      z-index: 1000;
      max-width: fit-content;
    }
  }
}
</style>

<script>
    var timeout

function fancyCursor(e) {

  let halfCursorSize = 12
  let halfTrailSize = 40
  let scaleMin = 0.35
  let scaleMax = 1.0
  let finalX = e.pageX - halfCursorSize
  let finalY = e.pageY - halfCursorSize
  let finalTrailX = e.pageX - halfTrailSize
  let finalTrailY = e.pageY - halfTrailSize
  
  document.querySelector('.cursor').style.transform = 'translate('+finalX+'px,'+finalY+'px) scale('+scaleMin+')'
  
  setTimeout(() => {
    document.querySelector('.cursor-trail').style.transform = 'translate('+finalTrailX+'px,'+finalTrailY+'px) scale('+scaleMin+')'
  }, 100)
  
  if(timeout !== undefined) {
    window.clearTimeout(timeout)
  }
  
  timeout = window.setTimeout(function() {
    document.querySelector('.cursor').style.transform = 'translate('+finalX+'px,'+finalY+'px) scale('+scaleMax+')'
    document.querySelector('.cursor-trail').style.transform = 'translate('+finalTrailX+'px,'+finalTrailY+'px) scale('+scaleMax+')'
  }, 250)
  document.querySelector('.cursor').style.opacity = '1'
  document.querySelector('.cursor-trail').style.opacity = '1'
}

function cursorLoader() {
  if(document.querySelector('.cursor__wrapper')) {
    document.querySelector('.cursor__wrapper').addEventListener('mousemove',fancyCursor)
    document.querySelector('.cursor__wrapper').addEventListener('mouseleave',() => {
      document.querySelector('.cursor').style.opacity = '0'
      document.querySelector('.cursor-trail').style.opacity = '0'
    },false)
    window.addEventListener('scroll', function (e) {
      document.querySelector('.cursor').style.opacity = '0'
      document.querySelector('.cursor-trail').style.opacity = '0'
    })
  }
}

window.addEventListener('load', function() {
  cursorLoader()
  function animate() {
    requestAnimationFrame(animate)
  }
  animate()
})
</script>