PGDMP  )                    }           postgres    17.4    17.4     �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            �           1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false            �           0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4828            �            1259    16474    comments    TABLE     �   CREATE TABLE public.comments (
    id integer NOT NULL,
    user_id character(1),
    post_id integer,
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    16480    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    217            �           0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    218            �            1259    16481    snips    TABLE     �  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variations text[],
    variation_of text
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    16490    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    219            �           0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    220            �            1259    16491    users    TABLE     �   CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[]
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    16496    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    221            �           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    222            +           2604    16497    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    218    217            -           2604    16498    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    220    219            2           2604    16499    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    222    221            �          0    16474    comments 
   TABLE DATA           M   COPY public.comments (id, user_id, post_id, content, created_at) FROM stdin;
    public               postgres    false    217   �"       �          0    16481    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variations, variation_of) FROM stdin;
    public               postgres    false    219   #       �          0    16491    users 
   TABLE DATA           \   COPY public.users (id, username, email, password, likedsnippets, savedsnippets) FROM stdin;
    public               postgres    false    221   >%       �           0    0    comments_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.comments_id_seq', 1, false);
          public               postgres    false    218            �           0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 33, true);
          public               postgres    false    220            �           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 3, true);
          public               postgres    false    222            4           2606    16501    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    217            6           2606    16503    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    219            8           2606    16505    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    221            :           2606    16507    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    221            <           2606    16509    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    221            =           2606    16510    comments comments_post_id_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_post_id_fkey FOREIGN KEY (post_id) REFERENCES public.snips(id);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_post_id_fkey;
       public               postgres    false    219    217    4662            >           2606    16515    comments comments_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(username);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_user_id_fkey;
       public               postgres    false    217    221    4668            ?           2606    16520    snips snips_creator_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username);
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    221    219    4668            �      x������ � �      �     x��Rێ�0|v���"lln��j�JUT)Q��b��[b�`�����$��F+�%��̙��y�;^� @\�>�v��x1Ʊ:~H	#`_��u�g(*qʀ�3F+p@������d��9lA��ن���� ������a�F4
��<R՝W��El }b.��e��x��3�Z��;�x̉܈h�����۸��}��q�ɓhZ���54��kS��w�[�w*5R+�2��]e�A��ԕnD3��T�>�b)����.ٻ&icf��h����ēd�#��D*�-��T:����Eߧ"zՖf��@bO�Rڛ�����!IL���!�'��)_.y�k���w)<��0�	��0�O�ˢ,���ޮ�/�[�_Gl�C��@�\�af�d]�����V��[i�G1%�K���!�?Һ��K�Ä(U,�-No�!dM3Z�GK\̞��"]R���w�o�^���\�[;��!��te�R�	�l+9���O�SW�Q���T�v�XW}��i:Z�=��f��>�@      �     x�m��N�@ E��,�a������%�B��c�� ς鿋.Ԥnn��{�$.�%�������1{��JL(�Y�	h$(\����[e��cР�zQ��خu}����t@����=���G�����o�%�~�-��M{BwpRFESg*j`��hB}�NEߐ��M�n;A3L�G�]���"���IJ���2�ul!_y��I�P�X6L����>8��l��l�{g���P�n��}�v��\��<��`����ѿ�����O*wv�     