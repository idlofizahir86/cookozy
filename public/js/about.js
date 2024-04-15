let controller = new ScrollMagic.Controller();
let t1 = gsap.timeline();

document.addEventListener('DOMContentLoaded', () => {

  t1.from(".section_1_01", 4, {
    y: -100,
    x: -150,
    ease: Power3.easeInOut
  });

  t1.from(".section_1_02", 4, {
    y: -150,
    x: -250,
    ease: Power3.easeInOut
  }, '-=4');

  t1.from(".section_1_03", 4, {
    y: -80,
    x: -100,
    ease: Power3.easeInOut
  }, '-=4');

  t1.from(".section_1_04", 4, {
    y: -100,
    x: -150,
    ease: Power3.easeInOut
  }, '-=4');

  t1.from(".section_1_05", 4, {
    y: -80,
    x: -200,
    ease: Power3.easeInOut
  }, '-=4');

  t1.from(".section_1_06", 4, {
    y: -80,
    x: -200,
    ease: Power3.easeInOut
  }, '-=4');

  t1.from(".section_1_07", 4, {
    y: -80,
    x: -200,
    ease: Power3.easeInOut
  }, '-=4');

  t1.from(".section_1_08", 4, {
    y: -80,
    x: -200,
    ease: Power3.easeInOut
  }, '-=4');
});

let t2 = gsap.timeline();
t2
.to('.top .image-container', 4, {
  height: 0
});

let scene2 = new ScrollMagic.Scene({
  triggerElement: '.top',
  duration: '100%',
  triggerHook: 0,
  offset: '100'
});

scene2.setTween(t2)
.setPin('.second-section')
.addTo(controller);

let t3 = gsap.timeline();
t3
.to('.section_3_01', 4, {
  y: -250,
  ease: Power3.easeInOut
})
.to('.section_3_02', 4, {
  y: -200,
  ease: Power3.easeInOut
}, '-=4');
