<template>
    <el-form ref="form" :model="form" :loading="loading">
        <!-- {{form.format}} -->        
        <div class="row mr-3">
            <div class="col-md-6 pr-5">
                <el-form-item :label="namelabel">
                    <el-input v-model="form.title" :placeholder="namelabel"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-6 pr-5">
                <el-form-item :label="authorlabel">
                    <el-input v-model="form.author" :placeholder="authorlabel"></el-input>
                </el-form-item>
            </div>
        </div>
        <div class="row mr-3">
            <div class="col-md-6 pr-5">
                <el-form-item :label="distribuitorlabel">
                    <el-input v-model="form.distribuitor" :placeholder="distribuitorlabel"></el-input>
                </el-form-item>
            </div>
            <div class="col-md-6 pr-5">
                <el-form-item label="Año">
                    <el-input v-model="form.year" placeholder="Año"></el-input>
                </el-form-item>
            </div>
        </div>
        <div class="row mr-3">
            <div class="col-md-6 pr-5">
                <el-form-item label="Descripcion">
                    <el-input v-model="form.description" placeholder="Descripcion"></el-input>
                </el-form-item>
            </div>
            <div class="col-12 col-lg-6">
                <el-form-item label="Genero">
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
                        <el-form-item label="Formato">
                            <el-select v-model="form.format" placeholder="Formato">
                                <el-option :label="format._name" :value="format.id" v-for="(format, index) in formats" :key="index"></el-option>
                            </el-select>
                        </el-form-item>
                    </div>
                    <div class="col-md-6 mt-auto">
                        <el-form-item label="Cantidad">
                            <el-input v-model="form.stock" type="number" placeholder="Cantidad"></el-input>
                        </el-form-item>
                    </div>
                </div>
            </div>
            <div class="col-md-6 pr-5">
                <el-form-item label="Edicion">
                    <el-select v-model="form.edition" placeholder="Edicion">
                        <el-option :label="edition._name" :value="edition.id" v-for="(edition, index) in editions" :key="index"></el-option>
                    </el-select>
                </el-form-item>
            </div>
        </div>
        <el-form-item label="Codigo de barras/ISBN">
            <el-input type="textarea" v-model="form.description" placeholder="Codigo de barras/ISBN" :rows="6"></el-input>
        </el-form-item>

        <div class="row mr-3">
            <div class="col-12 col-lg-6">
                <el-form-item label="Precio">
                    <el-input v-model="form.price" type="Precio" placeholder="Precio"></el-input>
                </el-form-item>
            </div>            
        </div>
        
        <el-form-item label="Imagenes">
        </el-form-item>
        <el-upload :auto-upload="false" :action="uploadEndpoint" ref="upload" list-type="picture-card" accept="image/*" :multiple="true"
        :on-preview="handlePictureCardPreview" :on-remove="handleRemove" :limit="6" :on-exceed="handleExceed" :on-success="handleSuccess">
            <i class="el-icon-plus"></i>
        </el-upload>
                
        <div class="row mr-3 justify-content-center mt-auto">
            <el-form-item class="mt-lg-3">
                <el-button type="primary" @click="uploadImages">{{ (entityprop)? "Edit":"Create" }}</el-button>
                <el-button>Cancel</el-button>
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
                genero: []
            },
            genres: [],
            formats: [],
            editions: [],
            files: [],
            productType: {},
        }
    },
    created () {
        this.genres = JSON.parse(this.genresprop);
        this.formats = JSON.parse(this.formatsprop);
        this.editions = JSON.parse(this.editionsprop);
        this.productType = JSON.parse(this.producttypeprop);
        if (this.entityprop) {
            this.form = JSON.parse(this.entityprop);
        }
    },
    methods: {
      handleRemove(file, fileList) {
        console.log(file, fileList);
      },
      handlePictureCardPreview(file) {
      },
      handleExceed(files, fileList) {
          this.$message.warning(`El límite es 6, haz seleccionado ${files.length} archivos esta vez, añade hasta ${files.length + fileList.length}`);
      },
      handleSuccess (response, file, fileList) {
          this.files.push({
              name: response.filename
          })
          if (this.files.length >= fileList.length) {
              console.log("Finished!", this.files)
              this.sendForm("form");
          }
      },
      uploadImages () {
          this.loading = true
          this.$refs.upload.submit();
      },
      sendForm (formName) {
          this.$refs[formName].validate((valid) => {
            if (valid) {
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

                if (this.entityprop)
                    formData.append("productID", this.form.id);

                axios.post(this.submit, formData).then(res => {
                    console.log("Resposse", res)
                    window.location.reload()
                }).finally(() => {
                    this.loading = false;
                })
            } else {
                console.log('error submit!!');
                return false;
            }
        });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
    }
}
</script>