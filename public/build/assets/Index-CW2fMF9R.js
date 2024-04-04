import{r as w,b as v,c as i,a as t,w as k,q as V,t as c,d as j,h as r,x as u,m as n,o as f,D as p,X as I,A as N}from"./app-Dr-oWWLk.js";import{_ as x,a as _}from"./Label-D4oBhNJ1.js";import{_ as U}from"./index-DJCjot5l.js";import{S as F}from"./save-BnBt_oDL.js";import"./index-CNWTbc8F.js";import"./createLucideIcon-BhFW6A1j.js";const C={class:"w-full box-border"},E={class:"w-full relative overflow-x-auto shadow-md sm:rounded-lg border border-border bg-card text-card-foreground shadow"},R=t("div",{class:"box-border px-6 py-6 border-b border-border flex justify-between items-center"},[t("div",null,"Update Profil")],-1),T={class:"relative overflow-x-auto w-full"},$={class:"w-full box-border px-3 py-6"},O={class:"w-full flex flex-col lg:flex-row gap-2 mb-5"},z={class:"w-full lg:w-1/2 flex items-center justify-center"},P={class:"mb-6 w-2/3"},B={class:"flex items-center justify-center w-full"},D={key:0},M=["src"],G={key:1,class:"flex flex-col items-center justify-center pt-5 pb-6"},L=I('<svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"></path></svg><p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span></p><p class="text-xs text-gray-500 dark:text-gray-400"> SVG, PNG, JPG or GIF (MAX. 2 mb) </p>',3),A=[L],J={key:0,class:"text-red-500 italic text-xs mt-1"},K={class:"w-full lg:w-1/2 space-y-4"},X={class:"w-full"},q={class:"w-full"},H={class:"text-[10px] italic font-light text-red-400"},Q={class:"w-full"},W={class:"w-full"},Y={class:"text-[10px] italic font-light text-red-400"},Z={class:"w-full"},ee={class:"w-full"},te={class:"text-[10px] italic font-light text-red-400"},se=t("div",{class:"text-sm"},"Simpan",-1),ce={__name:"Index",setup(oe){const m=w(localStorage.getItem("USER")?JSON.parse(localStorage.getItem("USER")):{}),{notify:g}=N(),d=w(),s=v({nama:null,username:null,password:null,foto:null}),a=v({nama:m.value.nama,username:m.value.username,password:null,foto:m.value.foto}),b=()=>{s.foto=null;const o=d.value.files[0],e=o.size,l=o.type,y=["image/jpg","image/jpeg","image/svg+xml","image/gif","image/png"],S=2*1048576;if(!y.includes(l)){s.foto="Type File must be jpg, jpeg, png, svg or gif";return}if(e>S){s.foto="file size cannot be larger than 2 mb";return}a.foto=URL.createObjectURL(o)},h=async()=>{try{const o=new FormData;o.append("nama",a.nama??""),o.append("username",a.username??""),o.append("password",a.password??""),o.append("gender","l"),d.value.files[0]!==void 0&&o.append("foto",d.value.files[0]);const{data:e}=await axiosInstance({url:"/account/update",method:"POST",headers:{"Content-Type":"multipart/form-data"},data:o});e.token&&(localStorage.removeItem("TOKEN"),localStorage.setItem("TOKEN",e.token)),e.userInfo&&(localStorage.removeItem("USER"),localStorage.setItem("USER",JSON.stringify(e.userInfo))),g({text:"Berhasil update profil",type:"success",duration:2e3}),location.reload()}catch(o){if(o.response.status==401)localStorage.removeItem("TOKEN"),location.reload();else if(o.response.status==422){const e=o.response.data.errors;s.nama=e.nama?e.nama[0]:null,s.username=e.username?e.username[0]:null,s.password=e.password?e.password[0]:null,s.foto=e.foto?e.foto[0]:null}else g({text:"Faliled to add, Server is Maintenent",type:"error",duration:2e3})}};return(o,e)=>(f(),i("div",C,[t("div",E,[R,t("div",T,[t("div",$,[t("form",{onSubmit:e[7]||(e[7]=k(l=>h(),["prevent"]))},[t("div",O,[t("div",z,[t("div",P,[t("div",B,[t("label",{for:"foto-ref",class:V(["flex flex-col items-center justify-center box-border w-full h-64 border-2 border-dashed rounded-lg cursor-pointer overflow-hidden",s.foto?"border-red-300":"border-border"])},[a.foto?(f(),i("div",D,[t("img",{src:a.foto,alt:"",loading:"lazy",class:"w-full object-cover object-center"},null,8,M)])):(f(),i("div",G,A)),t("input",{id:"foto-ref",ref_key:"fotoRef",ref:d,type:"file",class:"sr-only",onChange:e[0]||(e[0]=l=>b())},null,544)],2)]),s.foto?(f(),i("div",J,c(s.foto),1)):j("",!0)])]),t("div",K,[t("div",X,[r(n(x),{class:"block mb-px text-sm flex-none"},{default:u(()=>[p("Nama Lengkap")]),_:1}),t("div",q,[r(n(_),{onFocus:e[1]||(e[1]=l=>s.nama=null),type:"text",modelValue:a.nama,"onUpdate:modelValue":e[2]||(e[2]=l=>a.nama=l)},null,8,["modelValue"]),t("span",H,c(s.nama),1)])]),t("div",Q,[r(n(x),{class:"block mb-px text-sm flex-none"},{default:u(()=>[p("Username")]),_:1}),t("div",W,[r(n(_),{onFocus:e[3]||(e[3]=l=>s.username=null),type:"text",modelValue:a.username,"onUpdate:modelValue":e[4]||(e[4]=l=>a.username=l)},null,8,["modelValue"]),t("span",Y,c(s.username),1)])]),t("div",Z,[r(n(x),{class:"block mb-px text-sm flex-none"},{default:u(()=>[p("Password")]),_:1}),t("div",ee,[r(n(_),{onFocus:e[5]||(e[5]=l=>s.password=null),type:"password",modelValue:a.password,"onUpdate:modelValue":e[6]||(e[6]=l=>a.password=l)},null,8,["modelValue"]),t("span",te,c(s.password),1)])])])]),r(n(U),{type:"submit",variant:"outline",size:"lg",class:"w-full"},{default:u(()=>[r(n(F),{class:"w-5 h-5 mr-3"}),se]),_:1})],32)])])])]))}};export{ce as default};
