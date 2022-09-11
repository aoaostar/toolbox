var te=Object.defineProperty,ue=Object.defineProperties;var ne=Object.getOwnPropertyDescriptors;var x=Object.getOwnPropertySymbols;var se=Object.prototype.hasOwnProperty,oe=Object.prototype.propertyIsEnumerable;var A=(t,s,n)=>s in t?te(t,s,{enumerable:!0,configurable:!0,writable:!0,value:n}):t[s]=n,w=(t,s)=>{for(var n in s||(s={}))se.call(s,n)&&A(t,n,s[n]);if(x)for(var n of x(s))oe.call(s,n)&&A(t,n,s[n]);return t},N=(t,s)=>ue(t,ne(s));var E=(t,s,n)=>new Promise((v,d)=>{var y=i=>{try{m(n.next(i))}catch(l){d(l)}},k=i=>{try{m(n.throw(i))}catch(l){d(l)}},m=i=>i.done?v(i.value):Promise.resolve(i.value).then(y,k);m((n=n.apply(t,s)).next())});import{D as T,T as ae,B as le}from"./TableAction.a7e8a0fe.js";import{u as re,B as ie}from"./useForm.c0d75d1c.js";import{aW as D,v as f,k as ce,d as de,r as h,u as me,ba as _e,s as C,e as pe,x as fe,w as o,h as a,i as F,j as b,N as ge,b4 as he,y as Fe,z as ve,l as ye,A as ke,C as Be,D as we,E as be,n as Ee}from"./index.5d02c90a.js";import"./sortable.esm.ebf67857.js";import"./useDesignSetting.883c9893.js";import"./index.esm.2902f313.js";import"./DownOutlined.a77ae617.js";function Ce(t){return D.request({url:"/master/users",method:"get",params:t})}function S(t){return D.request({url:"/master/user",method:"put",params:t})}function O(t){return D.request({url:"/master/user",method:"delete",params:t})}const De=[{type:"selection",key:"selection"},{title:"id",key:"id",width:100,sorter:!0},{title:"\u7528\u6237\u540D",key:"username",width:200,sorter:!0},{title:"avatar",key:"avatar",width:160,render(t){return f(ce,{size:48,src:t.avatar})}},{title:"stars",key:"stars",width:160,render(t){return JSON.stringify(t.stars,null,4)}},{title:"\u521B\u5EFA\u65F6\u95F4",key:"create_time",width:160,sorter:!0},{title:"\u4FEE\u6539\u65F6\u95F4",key:"update_time",width:160,sorter:!0}],Ue=b(" \u5220\u9664 "),Re=b("\u5237\u65B0\u6570\u636E"),xe=b("\u53D6\u6D88"),Ae=b("\u786E\u5B9A"),Ne={name:"UserList"},Le=de(N(w({},Ne),{setup(t){const s={stars:{required:!0,trigger:["blur","change"],message:"\u8BF7\u8F93\u5165\u6B63\u786E\u7684stars"}},n=[{field:"username",labelMessage:"\u8BF7\u8F93\u5165\u9700\u8981\u641C\u7D22\u7684\u7528\u6237\u540D",component:"NInput",label:"\u7528\u6237\u540D",componentProps:{placeholder:"\u8BF7\u8F93\u5165\u7528\u6237\u540D",onInput:e=>{_.username=e,console.log(e)}}},{field:"id",label:"id"}],v=h(null),d=me(),y=_e(),k=h(),m=h(!1),i=h(!1),l=C({id:0,username:"",stars:JSON.stringify("[]",null,4)}),_=C({pageSize:10,username:"",column:"",order:""}),g=h([]),q=C({width:220,title:"\u64CD\u4F5C",key:"action",fixed:"right",render(e){return f(ae,{style:"button",actions:[{label:"\u7F16\u8F91",onClick:K.bind(null,e)},{label:"\u5220\u9664",icon:T,onClick:W.bind(null,e)}]})}}),[P,{}]=re({gridProps:{cols:1},collapsedRows:1,labelWidth:120,schemas:n,showAdvancedButton:!1});function I(){if(g.value.length==0){y.warning({title:"\u8B66\u544A",content:"\u8BF7\u81F3\u5C11\u9009\u62E9\u4E00\u9879"});return}let e=[];for(const u of g.value)e.push(u.username);y.warning({title:"\u8B66\u544A",content:()=>f("div",["\u60A8\u786E\u5B9A\u8981\u5220\u9664\u4EE5\u4E0B\u5171 ",f(ge,{type:"info"},g.value.length)," \u4E2A\u7528\u6237\u5417\uFF1F",f("br"),f(he,{},e.join("\u3001"))]),negativeText:"\u53D6\u6D88",positiveText:"\u786E\u5B9A",onPositiveClick(){let u=[];for(const r of g.value)u.push(O({id:r.id}).then(()=>{d.success("\u5220\u9664\u6210\u529F")}));Promise.all(u).finally(p)}})}const J=e=>E(this,null,function*(){return yield Ce(w(w({},_),e))});function z({column:e,value:u,record:r}){e.key==="id"&&(r.editValueRefs.name4.value=`${u}`),console.log(e,u,r)}function L({record:e,key:u,value:r}){S({id:e.id,[u]:r}).then(()=>{d.success("\u66F4\u65B0\u6210\u529F"),p()})}function M(e,u){g.value=u.filter(r=>r)}function p(){k.value.reload()}function V(e){e.preventDefault(),i.value=!0,v.value.validate(u=>E(this,null,function*(){if(u)d.error("\u8BF7\u586B\u5199\u5B8C\u6574\u4FE1\u606F");else try{yield S({id:l.id,stars:JSON.parse(l.stars)}),d.success("\u66F4\u65B0\u6210\u529F"),m.value=!1,p()}catch(r){d.error(r.message);return}i.value=!1}))}function K(e){m.value=!0,[l.id,l.username,l.stars]=[e.id,e.username,JSON.stringify(e.stars,null,4)]}function W(e){O({id:e.id}).then(()=>{d.success("\u5220\u9664\u6210\u529F"),p()})}function $(e){console.log(e),p()}function G(e){_.username="",console.log(e)}function H(e){console.log(e),e.order?[_.column,_.order]=[e.columnKey,e.order.slice(0,e.order.lastIndexOf("end"))]:[_.column,_.order]=["",""],p()}return(e,u)=>{const r=Fe,Q=ve,B=ye,U=ke,X=Be,Y=we,Z=be,j=Ee;return pe(),fe(j,{bordered:!1,class:"proCard"},{default:o(()=>[a(F(ie),{onRegister:F(P),onSubmit:$,onReset:G},{statusSlot:o(({model:c,field:R})=>[a(r,{value:c[R],"onUpdate:value":ee=>c[R]=ee},null,8,["value","onUpdate:value"])]),_:1},8,["onRegister"]),a(F(le),{columns:F(De),request:J,"row-key":c=>c.id,ref_key:"actionRef",ref:k,actionColumn:q,"onUpdate:checkedRowKeys":M,"scroll-x":1200,onEditEnd:L,onEditChange:z,"onUpdate:sorter":H},{tableTitle:o(()=>[a(B,{type:"primary",onClick:I},{icon:o(()=>[a(Q,null,{default:o(()=>[a(F(T))]),_:1})]),default:o(()=>[Ue]),_:1})]),toolbar:o(()=>[a(B,{type:"primary",onClick:p},{default:o(()=>[Re]),_:1})]),_:1},8,["columns","row-key","actionColumn"]),a(Z,{show:m.value,"onUpdate:show":u[3]||(u[3]=c=>m.value=c),"show-icon":!1,preset:"dialog",title:"\u7F16\u8F91"},{action:o(()=>[a(Y,null,{default:o(()=>[a(B,{onClick:u[2]||(u[2]=()=>m.value=!1)},{default:o(()=>[xe]),_:1}),a(B,{type:"info",loading:i.value,onClick:V},{default:o(()=>[Ae]),_:1},8,["loading"])]),_:1})]),default:o(()=>[a(X,{model:l,rules:s,ref_key:"formRef",ref:v,"label-placement":"left","label-width":80,class:"py-4"},{default:o(()=>[a(U,{label:"\u7528\u6237\u540D",path:"username"},{default:o(()=>[a(r,{placeholder:"\u8BF7\u8F93\u5165\u7528\u6237\u540D",value:l.username,"onUpdate:value":u[0]||(u[0]=c=>l.username=c),disabled:""},null,8,["value"])]),_:1}),a(U,{label:"stars",path:"stars"},{default:o(()=>[a(r,{placeholder:"\u8BF7\u8F93\u5165stars",value:l.stars,"onUpdate:value":u[1]||(u[1]=c=>l.stars=c),type:"textarea",rows:"10"},null,8,["value"])]),_:1})]),_:1},8,["model"])]),_:1},8,["show"])]),_:1})}}}));export{Le as default};