<template>
    <el-form ref="form" :model="form" :rules="rules" v-loading="loading">
        <!-- {{form.format}} -->      
        <div class="row mr-3">
            <div class="col-md-6 pr-5">
                <el-form-item :label="namelabel" prop="title">
                    <el-input v-model="form.title" :placeholder="namelabel"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-6 pr-5">
                <el-form-item :label="authorlabel" prop="author">
                    <el-input v-model="form.author" :placeholder="authorlabel"></el-input>
                </el-form-item>
            </div>
        </div>
        <div class="row mr-3">
            <div class="col-md-6 pr-5">
                <el-form-item :label="distribuitorlabel" prop="distribuitor">
                    <el-input v-model="form.distribuitor" :placeholder="distribuitorlabel"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-6 pr-5">
                <el-form-item label="Año" prop="year">
                    <el-input v-model="form.year" placeholder="Año"></el-input>
                </el-form-item>
            </div>
        </div>
        <div class="row mr-3">
            <div class="col-md-6 pr-5">
                <el-form-item label="Código de barras/ISBN" prop="description">
                    <el-input v-model="form.description" placeholder="Código de barras/ISBN"></el-input>
                </el-form-item>
            </div>
            <div class="col-12 col-lg-6">
                <el-form-item label="Género" prop="genero">
                    <el-checkbox-group v-model="form.genero">
                        <el-checkbox :label="genre" :name="genre" v-for="(genre, index) in genres" :key="index"></el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
            </div>
        </div>

        <div class="row mr-3">     
            <div class="col-md-6 pr-5">     
                <div class="row">  
                    <div class="col-md-6 pr-5">
                        <el-form-item label="Formato" prop="format">
                            <el-select v-model="form.format" placeholder="Formato">
                                <el-option :label="format._name" :value="format.id" v-for="(format, index) in formats" :key="index"></el-option>
                            </el-select>
                        </el-form-item>
                    </div>
                    <div class="col-md-6 mt-auto">
                        <el-form-item label="Cantidad" prop="stock">
                            <el-input v-model="form.stock" type="number" placeholder="Cantidad"></el-input>
                        </el-form-item>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pr-5">
                <el-form-item label="Edicion" prop="edition">
                    <el-select v-model="form.edition" placeholder="Edicion">
                        <el-option :label="edition._name" :value="edition.id" v-for="(edition, index) in editions" :key="index"></el-option>
                    </el-select>
                </el-form-item>
            </div> 

            <div class="col-12 col-lg-6" v-if="form.images != null">
                <el-form-item label="Imagenes actuales">
                </el-form-item>
                <el-carousel v-if="isEditing && form.images.length > 0">
                    <el-carousel-item v-for="(image, index) in form.images" :key="index">
                        <el-image class="image-cover" :src="`/uploads/products/${image.name}`" fit="cover"></el-image>
                        <el-tooltip class="item" effect="dark" content="Eliminar imagen" placement="top-start">
                            <el-button @click="removeImage (image)"
                                style="position: absolute; top: 0; right: 0; margin: 1rem;" type="danger" icon="el-icon-delete" circle></el-button>
                        </el-tooltip>
                    </el-carousel-item>
                </el-carousel>
            </div>

            <div class="col-12 col-lg-6">
                <el-form-item label="Precio" prop="price">
                    <el-input v-model="form.price" type="number" placeholder="Precio"></el-input>
                </el-form-item>
            </div>  
        </div>
        
        <el-form-item :label="((isEditing)? 'Añadir':'') + ' Imagenes'" v-model="form.files" prop="files" class="mt-4">
            <el-upload :auto-upload="false" :action="uploadEndpoint" ref="upload" list-type="picture-card" accept="image/*" :multiple="true"
            :on-preview="handlePictureCardPreview" :on-remove="handleRemove" :limit="6 - currImagesLength" :on-exceed="handleExceed" :on-success="handleSuccess"
            :on-change="handleOnChange">
                <i class="el-icon-plus"></i>
            </el-upload>
        </el-form-item>
                
        <div class="row mr-3 justify-content-center mt-auto">
            <el-form-item class="mt-lg-3">
                <el-button type="primary" @click="uploadImages">{{ (entityprop)? "Editar":"Crear" }}</el-button>
                <el-button @click="goBack">Cancelar</el-button>
            </el-form-item>
        </div>
    </el-form>
</template>

<script>
import axios from 'axios';

export default {
    props: {
        namelabel: {
            default: "Nombre"
        },
        distribuitorlabel: {
            default: "Distribuidor"
        },
        authorlabel: {
            default: "Autor"
        },
        genresprop: {
            default: () => { return [] },
        },
        formatsprop: {
            default: () => { return [] },
        },
        editionsprop: {
            default: () => { return [] },
        },
        uploadEndpoint: {
            default: "product/image-upload"
        },
        submit: {
            default: "product/image-upload"
        },
        producttypeprop: {
            default: {},
        },
        entityprop: {
            default: null
        }
    },
    data () {
        return {
            loading: false,
            form: {
                genero: [],
                files: [], // Para validar solamente
            },
            genres: [],
            formats: [],
            editions: [],
            files: [], // Para subir
            productType: {},
            rules: {
                title: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                author: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                year: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                distribuitor: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                format: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                stock: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                genero: [
                    { type: 'array', required: true, message: 'Selecciona al menos un genero', trigger: 'change' }
                ],
                edition: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                price: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                description: [
                    { required: true, message: 'Este campo es requerido', trigger: 'blur' }
                ],
                files: [
                    { required: true, message: 'Selecciona al menos una imagen', trigger: 'blur' }
                ],
            }
        }
    },
    created () {
        this.genres = JSON.parse(this.genresprop);
        this.formats = JSON.parse(this.formatsprop);
        this.editions = JSON.parse(this.editionsprop);
        this.productType = JSON.parse(this.producttypeprop);
        if (this.entityprop) {
            this.form = JSON.parse(this.entityprop);
            this.form.files = [];
            delete this.rules.files
        }
    },
    methods: {
      handleRemove(file, fileList) {
        console.log(file, fileList);
      },
      handlePictureCardPreview(file) {
          console.log("File preview", file)
      },
      handleOnChange(file) {
          console.log("File changed", file, this.form)
          this.form.files.push(file)
      },
      handleExceed(files, fileList) {
          this.$message.warning(`El límite es 6, haz seleccionado ${files.length} archivos esta vez, añade hasta ${files.length + fileList.length}`);
      },
      handleSuccess (response, file, fileList) {
          this.files.push({
              name: response.filename
          })
          if (this.files.length >= fileList.length) {
              this.form.files = this.files
              console.log("Finished!", this.files)
              this.createProduct ()
          }
      },
      uploadImages () {
          this.sendForm("form");
      },
      sendForm (formName) {
          this.$refs[formName].validate((valid) => {
              if (valid) {
                  this.loading = true
                  if (this.isEditing) {
                      if (this.form.files.length > 0)
                        this.$refs.upload.submit();
                    this.createProduct ()
                  } else {
                    this.$refs.upload.submit();
                  }
                
            } else {
                this.loading = false
                console.log('error submit!!');
                return false;
            }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      },
      createProduct () {
          let formData = new FormData ();
          formData.append("name", this.form.title);
          formData.append("author", this.form.author);
          formData.append("year", this.form.year);
          formData.append("distribuitor", this.form.distribuitor);
          formData.append("format", this.form.format);
          formData.append("stock", this.form.stock);
          formData.append("genres", JSON.stringify(this.form.genero));
          formData.append("edition", this.form.edition);
          formData.append("price", this.form.price);
          formData.append("description", this.form.description);
          formData.append("productType", this.productType.id);
          formData.append("images", JSON.stringify(this.files));
          if (this.isEditing)
            formData.append("currImages", JSON.stringify(this.form.images));

          if (this.entityprop)
              formData.append("productID", this.form.id);

          axios.post(this.submit, formData).then(res => {
              this.$notify({
                  type: "success",
                  title: "Producto guardado",
                  message: "Se ha realizado correctamente"
              })
              setTimeout(() => {
                  window.location.reload()
              }, 1000);
          }).catch((err) => {
              this.$notify({
                  type: "error",
                  title: "Ocurrio un error",
                  message: err
              })
          })
          .finally(() => {
              this.loading = false;
          })
        },
        removeImage (image) {
            const index = this.form.images.indexOf(image);
            console.log("Del", index)
            if (index > -1) {
                this.form.images.splice(index, 1);
            }
        },
        goBack () {
            window.location.href = '/'
        }
    },
    computed: {
        isEditing () {
            return this.entityprop != null && this.entityprop.length > 0
        },
        currImagesLength () {
            if (this.form.images)
                return (this.form.images.length)
            else 
                return 0
        }
    }
}
</script>